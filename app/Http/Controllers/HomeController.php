<?php

namespace App\Http\Controllers;

use App\Models\VirtualClass;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $classes = [];
        // If user is a student, give her a list of virtual classes
        if ($user->user_type === "Student") {
            $classes = VirtualClass::orderBy('name', 'asc')->get();
        }
        return view('home', compact('user', 'classes'));
    }
}
