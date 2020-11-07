
<html>
    <head>
        <title> OpenTok Getting Started </title>
        <style>
            body, html {
                background-color: gray;
                height: 100%;
            }

            #videos {
                position: relative;
                width: 100%;
                height: 100%;
                margin-left: auto;
                margin-right: auto;
            }

            #subscriber {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
            }

            #publisher {
                position: absolute;
                width: 360px;
                height: 240px;
                bottom: 10px;
                left: 10px;
                z-index: 100;
                border: 3px solid white;
                border-radius: 3px;
            }
        </style>
        <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    </head>
    <body>
        <div id="videos">
            <div id="subscriber"></div>
            <div id="publisher"></div>
        </div>

        <script type="text/javascript">
            var session;
            var connectionCount = 0;
            var apiKey = "{{env('VONAGE_API_KEY')}}";
            var sessionId = "{{$sessionId}}";
            var token = "{{$token}}";
            var publisher;

            function connect() {
                // Replace apiKey and sessionId with your own values:
                session = OT.initSession(apiKey, sessionId);
                session.on("streamCreated", function (event) {
                    console.log("New stream in the session: " + event.stream.streamId);
                    session.subscribe(event.stream, 'subscriber', {
                        insertMode: 'append',
                        width: '100%',
                        height: '100%'
                    });
                });
                session.on({
                    connectionCreated: function (event) {
                        connectionCount++;
                        alert(connectionCount + ' connections.');
                    },
                    connectionDestroyed: function (event) {
                        connectionCount--;
                        alert(connectionCount + ' connections.');
                    },
                    sessionDisconnected: function sessionDisconnectHandler(event) {
                        // The event is defined by the SessionDisconnectEvent class
                        alert('Disconnected from the session.');
                        document.getElementById('disconnectBtn').style.display = 'none';
                        if (event.reason == 'networkDisconnected') {
                            alert('Your network connection terminated.')
                        }
                    }
                });
                var publisher = OT.initPublisher('publisher', {
                    insertMode: 'append',
                    width: '100%',
                    height: '100%'
                }, error => {
                    if (error) {
                        alert(error.message);
                    }
                });
                // Replace token with your own value:
                session.connect(token, function (error) {
                    if (error) {
                        alert('Unable to connect: ', error.message);
                    } else {
                        // document.getElementById('disconnectBtn').style.display = 'block';
                        alert('Connected to the session.');
                        connectionCount = 1;
                        if (session.capabilities.publish == 1) {
                            session.publish(publisher);
                        } else {
                            alert("You cannot publish an audio-video stream.");
                        }
                    }
                });
            }

            connect();


        </script>
    </body>
</html>
