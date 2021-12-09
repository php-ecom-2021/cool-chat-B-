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
</style>
</head>
<body>

    <h1>Welcome to Cool-Chat-B-)</h1>

    <h2>Choose a chatroom to start chatting:</h2>
    <div class="chatrooms">
    <a href="/chat">Public chat</a>
    <a href="/chat/1">chat 1</a>
    <a href="/chat/2">chat 2</a>
    <a href="/chat/3">chat 3</a>
    <a href="/chat/4">chat 4</a>
    </div>


</body>
</html>