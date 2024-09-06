<?php
include("validate.php");
include("pkmnstorage.php");
include("userstorage.php");
include("auth.php");

session_start();

$userStorage = new UserStorage();
$auth = new Auth($userStorage);

if ($auth->is_authenticated()) {
    $user = $auth->authenticated_user();
} else {
    header("Location: login.php");
}

$errors = [];
$data = [];
if (count($_POST) > 0) {


    if (validate($data, $errors) && isset($user)) {

        $data["userId"] = $user["id"];
        $PkmnStorage = new PkmnStorage();
        $PkmnStorage->add($data);
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="styles/cards.css" />
<link rel="stylesheet" type="text/css" href="styles/details.css" />
<link rel="stylesheet" type="text/css" href="styles/main.css" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új kártya hozzáadása</title>
    <style>
        small {
            color: red
        }
    </style>
</head>
<header>
    <h1>IKémon > <?= $user["username"] ?> > Kártyakészítés</h1>
    
</header>
<body>
    <?php if (isset($user)) : ?>
        <h2>Új kártya létrehozása</h2>
    <?php endif ?>
    <form action="" method="post">
        Pokémon:
        <input type="text" name="pkmn" value="<?= isset($data["pkmn"]) ? $data["pkmn"] : "" ?>" />
        <?php if (isset($errors["pkmn"])) : ?>
            <small><?= $errors["pkmn"] ?></small>
        <?php endif ?>
        <br>
        Életerő:
        <input type="number" name="hp" value="<?= isset($data["hp"]) ? $data["hp"] : "" ?>" />
        <?php if (isset($errors["hp"])) : ?>
            <small><?= $errors["hp"] ?></small>
        <?php endif ?>
        <br>
        Támadás:
        <input type="number" name="atk" value="<?= isset($data["atk"]) ? $data["atk"] : "" ?>" />
        <?php if (isset($errors["atk"])) : ?>
            <small><?= $errors["atk"] ?></small>
        <?php endif ?>
        <br>
        Védelem:
        <input type="number" name="def" value="<?= isset($data["def"]) ? $data["def"] : "" ?>" />
        <?php if (isset($errors["def"])) : ?>
            <small><?= $errors["def"] ?></small>
        <?php endif ?>
        <br>
        Érték:
        <input type="number" name="cost" value="<?= isset($data["cost"]) ? $data["cost"] : "" ?>" />
        <?php if (isset($errors["cost"])) : ?>
            <small><?= $errors["cost"] ?></small>
        <?php endif ?>
        <br>
        Típus:
        <br>
        <input type="radio" name="type" value="grass" <?= isset($_POST["type"]) && $_POST["type"] === "grass" ? "checked" : "" ?>><label for="type">Grass</label>
        <input type="radio" name="type" value="fire" <?= isset($_POST["type"]) && $_POST["type"] === "fire" ? "checked" : "" ?>><label for="type">Fire</label>
        <input type="radio" name="type" value="water" <?= isset($_POST["type"]) && $_POST["type"] === "water" ? "checked" : "" ?>><label for="type">Water</label>
        <br>
        <input type="radio" name="type" value="bug" <?= isset($_POST["type"]) && $_POST["type"] === "bug" ? "checked" : "" ?>><label for="type">Bug</label>
        <input type="radio" name="type" value="flying" <?= isset($_POST["type"]) && $_POST["type"] === "flying" ? "checked" : "" ?>><label for="type">Flying</label>
        <input type="radio" name="type" value="normal" <?= isset($_POST["type"]) && $_POST["type"] === "normal" ? "checked" : "" ?>><label for="type">Normal</label>
        <br>
        <input type="radio" name="type" value="poison" <?= isset($_POST["type"]) && $_POST["type"] === "poison" ? "checked" : "" ?>><label for="type">Poison</label>
        <input type="radio" name="type" value="rock" <?= isset($_POST["type"]) && $_POST["type"] === "rock" ? "checked" : "" ?>><label for="type">Rock</label>
        <input type="radio" name="type" value="psychic" <?= isset($_POST["type"]) && $_POST["type"] === "psychic" ? "checked" : "" ?>><label for="type">Psychic</label>
        <br>
        <input type="radio" name="type" value="ground" <?= isset($_POST["type"]) && $_POST["type"] === "ground" ? "checked" : "" ?>><label for="type">Ground</label>
        <input type="radio" name="type" value="electric" <?= isset($_POST["type"]) && $_POST["type"] === "electric" ? "checked" : "" ?>><label for="type">Electric</label>
        <input type="radio" name="type" value="ice" <?= isset($_POST["type"]) && $_POST["type"] === "ice" ? "checked" : "" ?>><label for="type">Ice</label>
        <br>
        <input type="radio" name="type" value="dragon" <?= isset($_POST["type"]) && $_POST["type"] === "dragon" ? "checked" : "" ?>><label for="type">Dragon</label>
        <input type="radio" name="type" value="steel" <?= isset($_POST["type"]) && $_POST["type"] === "steel" ? "checked" : "" ?>><label for="type">Steel</label>
        <input type="radio" name="type" value="fairy" <?= isset($_POST["type"]) && $_POST["type"] === "fairy" ? "checked" : "" ?>><label for="type">Fairy</label>
        <br>
        <input type="radio" name="type" value="fighting" <?= isset($_POST["type"]) && $_POST["type"] === "fighting" ? "checked" : "" ?>><label for="type">Fighting</label>
        <input type="radio" name="type" value="dark" <?= isset($_POST["type"]) && $_POST["type"] === "dark" ? "checked" : "" ?>><label for="type">Dark</label>
        <input type="radio" name="type" value="ghost" <?= isset($_POST["type"]) && $_POST["type"] === "ghost" ? "checked" : "" ?>><label for="type">Ghost</label><?php if (isset($errors["type"])) : ?>
            <small><?= $errors["type"] ?></small>
            
        <?php endif ?>

        <br>
        Leírás
        <input type="text" name="desc" value="<?= isset($data["desc"]) ? $data["desc"] : "" ?>" />
        <?php if (isset($errors["desc"])) : ?>
            <small><?= $errors["desc"] ?></small>
        <?php endif ?>
        <br>
        Pokédex-Szám (A kártya képének beállításához szükséges):
        <input type="number" name="dex_num" value="<?= isset($data["dex_num"]) ? $data["dex_num"] : "" ?>" />
        <?php if (isset($errors["dex_num"])) : ?>
            <small><?= $errors["dex_num"] ?></small>
        <?php endif ?>
        <br>
        <button type="submit">Submit</button>
        <br>
        <a href="index.php">Vissza az admin oldalra</a>
    </form>
</body>

</html>