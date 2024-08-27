<?php
session_start();
function registerUser($name, $password)
{
    $dir = __DIR__ . "/users/$name";
    if (!file_exists($dir))
    {
        mkdir($dir, 0777, true);
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $usersFile = __DIR__ . "/Users.xml";
        if (file_exists($usersFile))
        {
            $xml->load($usersFile);
            $root = $xml->documentElement;
        }
        else
        {
            $root = $xml->createElement("users");
            $xml->appendChild($root);
        }
        $user = $xml->createElement("user");
        $user->setAttribute("name", htmlspecialchars($name));
        $passwordElement = $xml->createElement("password", htmlspecialchars($password));
        $user->appendChild($passwordElement);
        $root->appendChild($user);
        $xml->save($usersFile);
    }
    else
    {
        echo "User already exists.";
    }
}
function loginUser($name, $password)
{
    $usersFile = __DIR__ . "/Users.xml";
    if (file_exists($usersFile))
    {
        $xml = simplexml_load_file($usersFile);
        foreach ($xml->user as $user)
        {
            if ($user['name'] == $name && $user->password == $password)
            {
                $_SESSION['user'] = $name;
                setcookie("session_id", session_id(), time() + (86400 * 30), "/");
                header("Location: storage.php");
                exit();
            }
        }
    }
    echo "Wrong username or password.";
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST["name"];
    $password = $_POST["password"];
    if (isset($_POST["register"]))
    {
        registerUser($name, $password);
    }
    elseif (isset($_POST["login"]))
    {
        loginUser($name, $password);
    }
}
if (isset($_POST["submit"]))
{
    $text = $_POST["text"];
    $date = date("Y-m-d H:i:s");
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
    <label for="name">Name: </label>
    <input type="text" id="name" name="name" required><br>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" required><br>
    <button type="submit" name="register">Register</button>
    <button type="submit" name="login">Login</button>
</form>
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
