<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHAT DEMO</title>
    <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" data-auto-replace-svg="nest"></script>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background: #6CAFA9;
        font-family: 'Courier New', Courier, monospace;
    }

    h4{
        color: #044445;
    }

    div#messages {
        height: 60vh;
        width: 40vw;
        background: white;
        overflow: auto;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        box-shadow: 0px 0px 19px -4px rgb(0 0 0 / 45%);

       
    }
    .message {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        padding: 1rem;
        /* border-bottom: 1px solid lightgrey; */
    }
    .control{
        width: 40vw;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75px;
        box-shadow: 0px 0px 19px -4px rgb(0 0 0 / 45%);
        background: #efefef;
        
        
    }
    input#query {
        width: 45vw;
        height: 80%;
        border: none;
        outline: none;
        background: #fff;
        border-radius: 20px;
        margin: 0 5px;
        padding: 0 10px;
        font-family: 'Courier New', Courier, monospace;

    }
    input#user {
        height: 98%;
        background: #efefef;
        width: 7vw;
        border: none;
        font-family: 'Courier New', Courier, monospace;
        border-bottom-left-radius: 5px;
    }
    /*submit btn*/
    button#submit {
        height: 100%;
        width: 130px;
        font-size: 25px;
        font-weight: 600;
        color: #363636;
        border: none;
        font-family: 'Courier New', Courier, monospace;
        border-bottom-right-radius: 5px;
        box-shadow: -7px 3px 5px -8px rgb(0 0 0 / 21%);

    }
    button#submit:hover{
        background: #d6d6d6;
    }

    .content{
    margin: 0 5px 0 15px;
    padding: 10px;
    border-radius: 5px;
    background: #d4d4d4;
    }

    a{
        color: #f2f2f2;
        opacity: 1;
        font-weight: 600;
        text-decoration: none;
    }

    a:hover{
        opacity: .5;
    }

    p{
        margin: 15px 0 15px 0;
    }
    
    .backBtn{
        align-self: flex-start
    }
  

</style>
</head>
<body>
    <a class="backBtn" href="http://127.0.0.1">back</a>
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

    <p><a href="http://127.0.0.1:8000">Want to try another chat?</a></p>


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