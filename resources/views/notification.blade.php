<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('notification.store') }}" method="post">
        @csrf
        <label for="fname">Title</label>
        <input type="text" id="title" name="title" placeholder="Your Title..">

        <label for="subject">Content</label>
        <textarea id="content" name="content" placeholder="Write something.." style="height:200px"></textarea>
        <label for="subject">ID member</label>
        <textarea id="mem_id" name="mem_id" placeholder="Write something.." style="height:200px"></textarea>

        <input type="submit" value="Submit">

    </form>
</div>

<style>
    /* Style inputs with type="text", select elements and textareas */
    input[type=text], select, textarea {
        width: 100%; /* Full width */
        padding: 12px; /* Some padding */
        border: 1px solid #ccc; /* Gray border */
        border-radius: 4px; /* Rounded borders */
        box-sizing: border-box; /* Make sure that padding and width stays in place */
        margin-top: 6px; /* Add a top margin */
        margin-bottom: 16px; /* Bottom margin */
        resize: vertical /* Allow the user to vertically resize the textarea (not horizontally) */
    }

    /* Style the submit button with a specific background color etc */
    input[type=submit] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* When moving the mouse over the submit button, add a darker green color */
    input[type=submit]:hover {
        background-color: #45a049;
    }

    /* Add a background color and some padding around the form */
    .container {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        width: 500px;
        margin: 0 auto;
    }
    .alert-danger { color: red}
</style>

