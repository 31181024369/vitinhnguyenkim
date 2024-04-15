<!DOCTYPE html>
<html>
<head>
    <title>User Chat</title>
    <!-- Đưa các tệp CSS và JS cần thiết vào đây -->
</head>
<body>
<h1>User Chat</h1>

<div id="chat-messages"></div>

<form id="chat-form" action="{{ route('chat-message.store') }}" method="POST">
    @csrf
    <input type="text" name="message" id="message" placeholder="Type your message">
    <button type="submit">Send</button>
</form>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const chatForm = document.querySelector('#chat-form');
    const chatMessages = document.querySelector('#chat-messages');

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const messageInput = document.querySelector('#message');
        const message = messageInput.value;
        messageInput.value = '';

        axios.post(this.action, { message })
            .then(function (response) {
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
    });

    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true,
    });

    const channel = pusher.subscribe('chat-channel');
    channel.bind('chat-event', function (data) {
        const message = document.createElement('p');
        message.textContent = data.message;
        chatMessages.appendChild(message);
    });
</script>
      
</body>
</html>
