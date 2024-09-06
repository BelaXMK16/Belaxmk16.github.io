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
if (count($_GET) > 0) {
    if (isset($_GET["id"]) && trim($_GET["id"]) !== "") {
        
        $id = $_GET["id"];

        $pkmnStorge = new PkmnStorage();
        $data = $pkmnStorge->findById($id);

        if (count($_POST) > 0) {

            if (validate($data, $errors)) {
        
                $data["userId"] = $user["id"];
                $data["id"] = $id;
                $pkmnStorge->update($id, $data);
        
                header("Location: index.php");
                exit();
            }
        }
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
    <title>IKémon Kártyamódosítás</title>
    <style>
        small {
            color: red
        }
    </style>
</head>
<header>
    <h1>IKémon > Kártyamódosítás</h1>
    
</header>
<body>
    <a href="index.php">Listázó oldal</a>
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
        <input type="radio" name="type" value="grass" <?= isset($data["type"]) && $data["type"] === "grass" ? "checked" : "" ?>><label for="type">Grass</label>
        <input type="radio" name="type" value="fire" <?= isset($data["type"]) && $data["type"] === "fire" ? "checked" : "" ?>><label for="type">Fire</label>
        <input type="radio" name="type" value="water" <?= isset($data["type"]) && $data["type"] === "water" ? "checked" : "" ?>><label for="type">Water</label>
        <br>
        <input type="radio" name="type" value="bug" <?= isset($data["type"]) && $data["type"] === "bug" ? "checked" : "" ?>><label for="type">Bug</label>
        <input type="radio" name="type" value="flying" <?= isset($data["type"]) && $data["type"] === "flying" ? "checked" : "" ?>><label for="type">Flying</label>
        <input type="radio" name="type" value="normal" <?= isset($data["type"]) && $data["type"] === "normal" ? "checked" : "" ?>><label for="type">Normal</label>
        <br>
        <input type="radio" name="type" value="poison" <?= isset($data["type"]) && $data["type"] === "poison" ? "checked" : "" ?>><label for="type">Poison</label>
        <input type="radio" name="type" value="rock" <?= isset($data["type"]) && $data["type"] === "rock" ? "checked" : "" ?>><label for="type">Rock</label>
        <input type="radio" name="type" value="psychic" <?= isset($data["type"]) && $data["type"] === "psychic" ? "checked" : "" ?>><label for="type">Psychic</label>
        <br>
        <input type="radio" name="type" value="ground" <?= isset($data["type"]) && $data["type"] === "ground" ? "checked" : "" ?>><label for="type">Ground</label>
        <input type="radio" name="type" value="electric" <?= isset($data["type"]) && $data["type"] === "electric" ? "checked" : "" ?>><label for="type">Electric</label>
        <input type="radio" name="type" value="ice" <?= isset($data["type"]) && $data["type"] === "ice" ? "checked" : "" ?>><label for="type">Ice</label>
        <br>
        <input type="radio" name="type" value="dragon" <?= isset($data["type"]) && $data["type"] === "dragon" ? "checked" : "" ?>><label for="type">Dragon</label>
        <input type="radio" name="type" value="steel" <?= isset($data["type"]) && $data["type"] === "steel" ? "checked" : "" ?>><label for="type">Steel</label>
        <input type="radio" name="type" value="fairy" <?= isset($data["type"]) && $data["type"] === "fairy" ? "checked" : "" ?>><label for="type">Fairy</label>
        <br>
        <input type="radio" name="type" value="fighting" <?= isset($data["type"]) && $data["type"] === "fighting" ? "checked" : "" ?>><label for="type">Fighting</label>
        <input type="radio" name="type" value="dark" <?= isset($data["type"]) && $data["type"] === "dark" ? "checked" : "" ?>><label for="type">Dark</label>
        <input type="radio" name="type" value="ghost" <?= isset($data["type"]) && $data["type"] === "ghost" ? "checked" : "" ?>><label for="type">Ghost</label><?php if (isset($errors["type"])) : ?>
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