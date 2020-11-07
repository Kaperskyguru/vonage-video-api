<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualClass;

#Import necessary classes from the Vonage API (AKA OpenTok)
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\Role;


class SessionsController extends Controller
{

    /** Creates a new virtual class for teachers
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createClass(Request $request)
    {
        // Get the currently signed in user
        $user = $request->user();
        // Throw 403 if student tries to create a class
        if ($user->user_type === "Student") return back(403);
        // Instantiate a new OpenTok object with our api key & secret
        $opentok = new OpenTok(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        // Creates a new session (Stored in the Vonage API cloud)
        $session = $opentok->createSession(array('mediaMode' => MediaMode::ROUTED));
        // Create a new virtual class that would be stored in db
        $class = new VirtualClass();
        // Generate a name based on the name the teacher entered
        $class->name = $user->name . "'s " . $request->input("name") . " class";
        // Store the unique ID of the session
        $class->session_id = $session->getSessionId();
        // Save this class as a relationship to the teacher
        $user->myClass()->save($class);
        // Send the teacher to the classroom where real-time video goes on
        return redirect()->route('classroom', ['id' => $class->id]);
    }

    public function showClassRoom(Request $request, $id)
    {
        // Get the currently authenticated user
        $user = $request->user();
        // Find the virutal class associated by provided id
        $virtualClass = VirtualClass::findOrFail($id);
        // Gets the session ID
        $sessionId = $virtualClass->session_id;
        // Instantiates new OpenTok object
        $opentok = new OpenTok(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        // Generates token for client as a publisher that lasts for one week
        $token = $opentok->generateToken($sessionId, ['role' => Role::PUBLISHER, 'expireTime' => time() + (7 * 24 * 60 * 60)]);
        // Open the classroom with all needed info for clients to connect
        return view('classroom', compact('token', 'user', 'sessionId'));

    }

}
