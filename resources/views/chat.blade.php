<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHAT DEMO</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-auto-replace-svg="nest"></script>

</head>
<body>
    <section class="flex-container">
        <!-- Sti virker ikke -->
        <a class="backBtn" href="http://127.0.0.1">Go back</a>
        <h2>Welcome to {{ $id ?? '' }}. Chat away!</h2>
        <div id="messages"></div>

        <div class="control">
            <input type="text" id="user" autocomplete="off">
            <input type="text" id="query">
            <button id="submit">Send</button>
            
        </div>

        <template id="message">
            <div class="message">
                
                <div class="user"></div>
                <div class="content"></div>
            </div>
        </template>

        <p><a href="http://127.0.0.1:8000">  Want to try another chat?</a></p>
    </section>



    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script>
        // create a random user
        let user = document.querySelector('#user');
        @if (Auth::user())
        user.value = "{{ Auth::user()->name }}";
        // if logged in, don't change name
        user.readOnly = true;
            @else
        user.value = ('User #' + Math.floor(Math.random() * 100));
        @endif

        // method to add message to our chatbox
        function addMessageToBoard(message){
            let messageContainer = document.querySelector('#messages');
            let template = document.querySelector('#message');        
            let clone = template.content.cloneNode(true);
            
            let userElement = clone.querySelector('.user');
            let contentElement =  clone.querySelector('.content');

            userElement.innerText = message.user;
            contentElement.innerText = message.content;

            messageContainer.appendChild(clone);
        }

        // method to send a message
        function sendMessage()
        {
            let query = document.querySelector('#query');
            const data = { user: user.value, content: query.value, channelID: '{{ $id ?? '' }}' };

            query.value = '';
            query.setAttribute('disabled','true');
            console.log('Sending',data);

            fetch('/broadcast', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            })
            .finally(() => {
                query.removeAttribute('disabled');
                query.focus();
            });
        }


        // Pusher setup
        Pusher.logToConsole = true;
        var pusher = new Pusher('820db3c820484c910a8b', {cluster: 'eu'});

        // Pusher channel subscription
        var channel = pusher.subscribe('messages{{ $id ?? '' }}');
        channel.bind('message', function(data) {
            console.log('Receiving', data);
            addMessageToBoard(data);
        });


        // incase we want history, load messages from server
        let messages = {!! $messages !!};
        // add messages to board
        messages.forEach((message) => {
            addMessageToBoard(message);
        });

        // Attach event to send chat message on button click
        let button = document.querySelector('#submit');
        button.addEventListener('click', () => {
        sendMessage();
        });
        // event on input listening on Enter Key
        let query = document.querySelector('#query');
        query.addEventListener('keydown', (event) => {
            if(event.key === 'Enter'){
                sendMessage();
            }
        });
    </script>
</body>
</html>