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
$marketcounter=0;
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
function hasmoney($user,$cost){
    if($user["money"]<$cost){
        return "red";
    }
    else{
        return "white";
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
    <title>IKémon Piac</title>
    
</head>
<header>
    <h1><a href="login.php">IKémon</a> > <a href="index.php"><?= $user["username"] ?></a> > Piac</h1>
    <?php if (isset($user)) : ?>
        
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
    Jelenlegi egyenleged: <?= $user["money"] ?>💵
</header>
<body>
    <div class="bodyheader">

    <h3>A megvehető kártyák:</h3>
    <a href="buyrandom.php" method="post">
                                <h3 style="text-align: center;color:<?=hasmoney($user,500)?>;background-color:#A0830E;">
                                    Véletlenszerű kártya vásárlása: 500💵
                                </h3>
                            </a>
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

            <?php foreach ($pkmns as $id => $pkmn) : ?>
                <?php if ((isset($user) && $pkmn['userId']==="0") && $auth->authorize(["user"]))  : ?>
                    <div class="pokemon-card">
                        <?php $marketcounter+=1;?>
                        <div class = "image clr-<?=$pkmn["type"]?>">
                        <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/<?=str_pad($pkmn["dex_num"],3,"0",STR_PAD_LEFT);?>.png" alt=""> <br>
                        </div>
                        <div class="details">
                        <a href="card_viewer.php?id=<?=$pkmn["id"]?>" > <h2><?= $pkmn["pkmn"] ?><br></h2> </a>
                            <span class="card-type"> 
                                <span class="icon">🏷</span>
                                <?= $pkmn["type"] ?>
                            </span>
                            <b>🧡<?= $pkmn["hp"] ?> 🏹<?= $pkmn["atk"] ?> 🛡️<?= $pkmn["def"] ?></b>
                        </div>
                        <a href="buy.php?id=<?= $id ?>" method="post">
                                <div class="buy" style="color:<?=hasmoney($user,$pkmn["cost"])?>;">
                                    💰<?= $pkmn["cost"] ?>
                                </div>
                            </a>
                    
                    </div>
                    <?php endif?>
            <?php endforeach ?>

            <?php if($marketcounter==0 && $auth->authorize(["user"])) : ?>
                A Piac most üres, kérlek nézz vissza később!
            <?php endif ?>
            <?php if($auth->authorize(["admin"])) : ?>
                Admin felhasználók nem használhatják a piacot!
            <?php endif ?>
            

    </div>
</body>

</html>