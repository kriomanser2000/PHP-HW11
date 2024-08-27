<?php
if (isset($_COOKIE["submit"]))
{
    $text = $_POST["text"];
    $date = $_POST["Y-m-d H:i:s"];
    setcookie("text", $text, time() + 30 * 24 * 60 * 60);
    setcookie("date", $date, time() + 30 * 24 * 60 * 60);
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form method="post">
    <label for="text">Enter Text: </label>
    <input type="text" id="text" name="text" required>
    <button type="submit" name="submit">Save to Cookie</button>
</form>
<?php
if (isset($_COOKIE['text']) && isset($_COOKIE['date']))
{
    echo "<p>Saved txt: " . htmlspecialchars($_COOKIE['text']) . "</p>";
    echo "<p>Data save: " . htmlspecialchars($_COOKIE['date']) . "</p>";
}
?>
</body>
</html>