<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background:  #6CAFA9;
        font-family: 'Courier New', Courier, monospace;
    }
    .chatrooms{
        display: flex;
        flex-direction: column;
    }
    h1, h2{
        color: #044445;
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
    
    .newChatField{
        margin-top: 5rem
    }

    .newChatField a{
    text-align: center; 
    display: block;
}

    .newChatField input{
    width: 100%   
    }

    .DEBUG{
        color:red;
        margin-top: 5rem
    }
</style>
</head>
<body>

    <h1>Welcome to Cool-Chat-B-)</h1>

    <h2>Choose a chatroom to start chatting:</h2>
    <div class="chatrooms">
    <a href="/chat">Public chat</a>
    @foreach ($chatRooms as $chatRoom)
    <a href="/chat/{{ $chatRoom->chatroomID }}">[{{ $chatRoom->chatroomID }} Chat]</a>
    @endforeach
    </div>
    <div class="newChatField">
        <a id="roomLink" href="/">Create new chat</a>
        <input type="text" id="newRoom" >
    </div>
    <a class="DEBUG" href="/expire">[DEBUG] Expire all chats</a>

    <script>
let input = document.querySelector("#newRoom");
let link = document.querySelector("#roomLink");
input.addEventListener('input', ()=>{
    link.href = "/chat/"+input.value
    link.text = `Create new ${input.value} chat`
    // TODO: check if the value is already created and replace "create" with "join"
});
input.addEventListener('keydown', (e)=>{
    console.log(e.code)
    if (e.code == ("Enter" || "NumpadEnter")) {
        window.location = "/chat/"+input.value
    }
});
    </script>

</body>
</html>