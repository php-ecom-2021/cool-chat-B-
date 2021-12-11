<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Channels overview</title>
</head>
<body>
    <a href="../chat/">[All Chat]</a>
 {{-- possibly add js timer to show when expires --}}
    @foreach ($chatRooms as $chatRoom)
    <a href="../chat/{{ $chatRoom->chatroomID }}">[{{ $chatRoom->chatroomID }} Chat]</a>
    @endforeach
    <div>
        <input type="text" id="newRoom">
        <a id="roomLink" href="/">Create new chat</a>
        <a href="/channels/expire">Expire all chats</a>

        <script>
let input = document.querySelector("#newRoom");
let link = document.querySelector("#roomLink");
input.addEventListener('input', ()=>{
    link.href = "../chat/"+input.value
    link.text = `Create new ${input.value} chat`
    // TODO: check if the value is already created and replace "create" with "join"
});
        </script>
    </div>
</body>
</html>