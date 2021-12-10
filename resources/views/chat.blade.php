<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHAT DEMO</title>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background: #2c2f38;
    }

    div#messages {
        height: 60vh;
        width: 50vw;
        background: white;
        overflow: auto;
    }
    .message {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid lightgrey;
    }
    .control{
        width: 50vw;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75px;
    }
    input#query {
        width: 50vw;
        height: 100%;
    }
    input#user {
        height: 100%;
        background: white;
        width: 7vw;
    }
    button#submit {
        height: 100%;
        width: 130px;
    }
</style>
</head>
<body>
    <p>{{ $id ?? '' }} UPDATED</p>
<div id="messages">
    @php
    if ($chatRooms ?? false) {
        # code...
        for ($i=0; $i < count($chatRooms); $i++) { 
            echo "roomID: ".$chatRooms[$i]->chatroomID;
            echo "<br>";
            echo "expires: ".$chatRooms[$i]->expireDate;
            echo "<br>";
        }
    }
@endphp
</div>

<div class="control">
    <input type="text" id="user" autocomplete="off">
    <input type="text" id="query">
    <button id="submit">Go!</button>
</div>

<template id="message">
    <div class="message">
        <div class="user"></div>
        <div class="content"></div>
    </div>
</template>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script>
    // create a random user
    let user = document.querySelector('#user');
    user.value = 'User #' + Math.floor(Math.random() * 100);

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