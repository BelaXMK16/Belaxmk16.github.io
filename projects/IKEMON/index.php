<?php
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

$pkmns = [];

$pkmnstorage = new PkmnStorage();
$tablenum=0;
$filter = "";
if (isset($_GET["type"])) {
    $filter = $_GET["type"];
    $pkmns = $pkmnstorage->findMany(function ($pkmn) use ($filter) {
        return (str_contains($pkmn['type'], $filter));
    });
    
} else {
    $pkmns = $pkmnstorage->findMany(function($pkmn) use ($user) {
        return $pkmn;
    });
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
    <title>IKémon Home</title>
    
</head>
<header>
    <h1><a href="login.php">IKémon</a> > <a href="index.php"><?= $user["username"] ?></a></h1>
    <?php if (isset($user)) : ?>
        Jelenlegi egyenleged: <?= $user["money"] ?>💵<br>
        E-mail cím: <?= $user["email"] ?>
        <form action="logout.php" method="post">
            <button type="submit">Kijelentkezés</button>
        </form>
    <?php else: ?>
        <form action="login.php" method="post">
            <button type="submit">Bejelentkezés</button>
        </form>
        <form action="register.php" method="post">
            <button type="submit">Regisztráció</button>
        </form>
    <?php endif ?>

</header>
<body>
    <div class="bodyheader">
    <h3 style="text-align: center;background-color:#A0830E;"><a style="color:white; " href="market.php">Ugrás a piacra</a></h3>
    <?php if($auth->is_admin()) : ?>
        <form action="add.php" method="get">
        <button type="submit">Új kártya létrehozása</button>
        </form>
        
    <?php endif ?>
        <h3>A kártyáid:</h3>
    <?php 
    $usercount=0;
        foreach ($pkmns as $key => $pkmn){
            if($pkmn["userId"]==$user["id"]){
                $usercount+=1;
            }
        }
    ?>
    <?php if ($usercount==5)  : ?>
        <h3 style="color:red;"> Elérted a gyűjthető kártyák maximum mennyiségét (5)!</h3>
    <?php endif?>

    <form action="" method="get">
        Filter:
        <br>
        <input type="radio" name="type" value="grass" <?= isset($_POST["type"]) && $_POST["type"] === "grass" ? "checked" : "" ?>><label for="type">Grass</label>
        <input type="radio" name="type" value="fire" <?= isset($_POST["type"]) && $_POST["type"] === "fire" ? "checked" : "" ?>><label for="type">Fire</label>
        <input type="radio" name="type" value="water" <?= isset($_POST["type"]) && $_POST["type"] === "water" ? "checked" : "" ?>><label for="type">Water</label>
        <input type="radio" name="type" value="bug" <?= isset($_POST["type"]) && $_POST["type"] === "bug" ? "checked" : "" ?>><label for="type">Bug</label>
        <input type="radio" name="type" value="flying" <?= isset($_POST["type"]) && $_POST["type"] === "flying" ? "checked" : "" ?>><label for="type">Flying</label>
        <input type="radio" name="type" value="normal" <?= isset($_POST["type"]) && $_POST["type"] === "normal" ? "checked" : "" ?>><label for="type">Normal</label>
        <input type="radio" name="type" value="poison" <?= isset($_POST["type"]) && $_POST["type"] === "poison" ? "checked" : "" ?>><label for="type">Poison</label>
        <input type="radio" name="type" value="rock" <?= isset($_POST["type"]) && $_POST["type"] === "rock" ? "checked" : "" ?>><label for="type">Rock</label>
        <input type="radio" name="type" value="psychic" <?= isset($_POST["type"]) && $_POST["type"] === "psychic" ? "checked" : "" ?>><label for="type">Psychic</label>
        <input type="radio" name="type" value="ground" <?= isset($_POST["type"]) && $_POST["type"] === "ground" ? "checked" : "" ?>><label for="type">Ground</label>
        <input type="radio" name="type" value="electric" <?= isset($_POST["type"]) && $_POST["type"] === "electric" ? "checked" : "" ?>><label for="type">Electric</label>
        <input type="radio" name="type" value="ice" <?= isset($_POST["type"]) && $_POST["type"] === "ice" ? "checked" : "" ?>><label for="type">Ice</label>
        <input type="radio" name="type" value="dragon" <?= isset($_POST["type"]) && $_POST["type"] === "dragon" ? "checked" : "" ?>><label for="type">Dragon</label>
        <input type="radio" name="type" value="steel" <?= isset($_POST["type"]) && $_POST["type"] === "steel" ? "checked" : "" ?>><label for="type">Steel</label>
        <input type="radio" name="type" value="fairy" <?= isset($_POST["type"]) && $_POST["type"] === "fairy" ? "checked" : "" ?>><label for="type">Fairy</label>
        <input type="radio" name="type" value="fighting" <?= isset($_POST["type"]) && $_POST["type"] === "fighting" ? "checked" : "" ?>><label for="type">Fighting</label>
        <input type="radio" name="type" value="dark" <?= isset($_POST["type"]) && $_POST["type"] === "dark" ? "checked" : "" ?>><label for="type">Dark</label>
        <input type="radio" name="type" value="ghost" <?= isset($_POST["type"]) && $_POST["type"] === "ghost" ? "checked" : "" ?>><label for="type">Ghost</label>
    
    <br>    
    <button type="submit">Szűrés</button>
    </form>
    </div>
    <div id="card-list">
            <?php $marketcounter=0;
             foreach ($pkmns as $id => $pkmn) : ?>
                <?php if ((isset($user) && $user['id']==$pkmn['userId']))  : ?>

                    <?php $marketcounter+=1;?>

                    <div class="pokemon-card">
                    
                        <div class = "image clr-<?=$pkmn["type"]?>">
                        <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/<?=str_pad($pkmn["dex_num"],3,"0",STR_PAD_LEFT);?>.png" alt=""> <br>
                        </div>
                        <div class="details">
                            <a href="card_viewer.php?id=<?=$id?>" > <h2><?= $pkmn["pkmn"] ?><br></h2> </a>
                            <span class="card-type"> 
                                <span class="icon">🏷</span>
                                <?= $pkmn["type"] ?>
                            </span>
                            <b>🧡<?= $pkmn["hp"] ?> 🏹<?= $pkmn["atk"] ?> 🛡️<?= $pkmn["def"] ?></b>
                        </div>
                        
                        <?php if (isset($user) && $auth->authorize(["user"])) : ?>
                            <a href="sell.php?id=<?= $id ?>" method="post">
                                <div class="buy">
                                    💰<?= $pkmn["cost"]*0.9 ?>
                                </div>
                            </a>
                        <?php endif?>
                            <?php if (isset($user) && $auth->authorize(["admin"])) : ?>
                                <div class="buy">
                                    💰<?= $pkmn["cost"]*0.9 ?>
                                </div>
                                <form action="modify.php?id=<?=$pkmn["id"]?>" method="post">
                                    <button type="submit">Módosítás</button>
                                </form>

                            <?php endif?>
                        
                    
                    </div>
                    <?php endif?>
            <?php endforeach ?>

            <?php if($marketcounter==0) : ?>
                <a href="market.php"> Nincsenek kártyáid, a piacon tudsz venni kártyát!</a>
            <?php endif ?>
    </div>
</body>

</html>