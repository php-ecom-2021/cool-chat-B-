@extends('layouts.app')

@section('content')

    <div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{ __('You are logged in!') }}
    </div>

    <section class="flex-container">
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
    </section>

@endsection

