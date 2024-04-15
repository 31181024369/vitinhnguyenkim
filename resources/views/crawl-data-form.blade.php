
<form method="POST" action="/crawl-data">
    @csrf
    <label for="path">Enter the new path:</label>
    <input type="text" id="path" name="path" placeholder="/laptop-asus">
    <button type="submit" >Get Data</button>
</form>
