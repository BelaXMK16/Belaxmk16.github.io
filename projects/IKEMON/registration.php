<?php
include('userstorage.php');
include('auth.php');

function validate(&$data, &$errors)
{
    if (isset($_POST["username"]) && trim($_POST["username"]) !== "") {
        $data["username"] = $_POST["username"];
    } else {
        $errors["username"] = "Felhasználó név megadása kötelező!";
    }

    if (isset($_POST["password"]) && trim($_POST["password"]) !== "") {
        $data["password"] = $_POST["password"];
    } else {
        $errors["password"] = "Jelszó megadása kötelező!";
    }
    if (isset($_POST["email"])) {
        if (trim($_POST["email"]) !== "") {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "E-mail formátumnak kell lennie";
            }
            else{
                $data["email"]=$_POST["email"];
            }
        }
        else {
            $errors["email"] = "E-mail megadása kötelező!";
        }

    } else {
        $errors["email"] = "E-mail megadása kötelező!";
    }

    return count($errors) === 0;
}

$userStorage = new UserStorage();
$auth = new Auth($userStorage);
$errors = [];
$data = [];
if (count($_POST) > 0) {
    if (validate($data, $errors)) {
        if ($auth->user_exists($data['username'])) {
            $errors['global'] = "Ez a felhasználó már létezik";
        } else {
            $auth->register($data);
            header("Location: login.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
</head>
<link rel="stylesheet" type="text/css" href="styles/cards.css" />
<link rel="stylesheet" type="text/css" href="styles/details.css" />
<link rel="stylesheet" type="text/css" href="styles/main.css" />
<header>
<h1><a href="index.php">IKémon</a> > Regisztráció</h1>
</header>
<body>
<div class="bodyheader">
    <form action="" method="post">
        <p><?= $errors['global'] ?? "" ?></p>
        <label for="username">Felhasználó név:</label>
        <input type="text" name="username" value="<?= $data["username"] ?? "" ?>">
        <small><?= $errors["username"] ?? ""  ?></small>
        <br>
        <label for="password">Jelszó:</label>
        <input type="password" name="password" value="<?= $data["password"] ?? "" ?>">
        <small><?= $errors["password"] ?? ""  ?></small>
        <br>
        <label for="email">E-mail:</label>
        <input type="text" name="email" value="<?= $data["email"] ?? "" ?>">
        <small><?= $errors["email"] ?? ""  ?></small>
        <br>
        <button type="submit">Regisztráció</button>
    </form>
    </div>
</body>

</html>