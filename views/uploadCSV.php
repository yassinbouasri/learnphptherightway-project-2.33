<!DOCTYPE html>
<html>
<body>

<p>Click on the "Choose File" button to upload a file:</p>

<form action="/uploadCSV" method="post" enctype="multipart/form-data">
    <input type="file" id="myFile" name="filename">
    <input type="submit">
</form>

</body>
</html>