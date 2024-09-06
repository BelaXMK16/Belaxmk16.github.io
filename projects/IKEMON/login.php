<?php
include('userstorage.php');
include('pkmnstorage.php');
include('auth.php');
session_start();

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

    return count($errors) === 0;
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);

$errors = [];
$data = $_SESSION["user"] ?? [];
$user=null;
if (count($_POST) > 0) {
    if (validate($data, $errors)) {
        if ($auth->user_exists($data["username"])) {
            $user = $auth->authenticate($data["username"], $data["password"]);
            if ($user == NULL) {
                $errors["global"] = "Felhasználóhoz tartozó jelszó helytelen!";
            }   
            else {
                $auth->login($user);
            }
        } 
        else {
            $errors["global"] = "Nincs a megadott névhez tartozó felhasználó!";
        }
    }
}

$pkmns = [];
$pkmnstorage = new PkmnStorage();
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
if ($auth->is_authenticated()) {
    $user = $auth->authenticated_user();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
</head>
<link rel="stylesheet" type="text/css" href="styles/cards.css" />
<link rel="stylesheet" type="text/css" href="styles/details.css" />
<link rel="stylesheet" type="text/css" href="styles/main.css" />
<header>
<h1><a href="login.php">IKémon</a> > <?= $auth->is_authenticated() ? "<a href=\"index.php\">".$user["username"]."</a>" : "Bejelentkezés" ?></h1>
<?php if (!$auth->is_authenticated()) : ?>
        <p><?= $errors["global"] ?? "" ?></p>
        <form action="" method="post">
            <label for="username">Felhasználó név:</label>
            <input type="text" name="username" value="<?= $data["username"] ?? "" ?>">
            <small><?= $errors["username"] ?? ""  ?></small>
            <br>
            <label for="password">Jelszó:</label>
            <input type="password" name="password" value="<?= $data["password"] ?? "" ?>">
            <small><?= $errors["password"] ?? ""  ?></small>
            <br>
            <button type="submit">Bejelentkezés</button>
            </form>
            <form action="registration.php" method="post">    
            <button type="submit">Regisztráció</button>
            </form>

    <?php else:?>
        <form action="logout.php" method="post">
            <button type="submit">Kijelentkezés</button>
        </form>
    <?php endif ?>
</header>
<body>
<div class="bodyheader">
    
    
    <p>Üdvözöllek az IKémon NFT világában, ahol az online kártyagyűjtés egyedülálló élményét élheted meg a non-fungible tokenek (NFT) varázslatos világában! Az IKémon egy forradalmi online kártyajáték, amelyben az egyedi digitális eszközök, az NFT-k, döntenek a győzelem sorsáról.

<h3>Játékmenet:</h3>
Az IKémon játékban a hagyományos kártyajáték alapelvei ötvöződnek az NFT-k innovatív világával. 
Gyűjtsd össze a saját IKémon NFT kártyáidat, amelyek minden egyes egyedi és átruházható token egyedi tulajdonságokkal rendelkeznek. 
Alkosd meg a stratégiád és versenyezz más játékosokkal az IKémon NFT világában!

<h3>Funkciók:</h3>
<ul>
<li>
<b>Egyedi NFT-k:</b> Gyűjts egyedi IKémon-okat, amelyek rendelkeznek saját tulajdonságokkal, típusokkal és különböző értékekkel.
</li>
<li>
<b>Token Alapú Versengés: </b>Indulj el az online versenyekben és tornákon, ahol az NFT-k értéke és tulajdonságai döntik el, ki lesz a győztes.
</li>
<li>
<b>NFT Kereskedés:</b> Csatlakozz az IKémon piachoz, ahol az egyedi kártyákat cserélheted vagy eladhatod más játékosoknak.
</li>
<li>
<b>Kihívások és Események:</b> Vedd fel a kesztyűt különféle kihívások és események során, hogy ritka NFT-ket és egyéb jutalmakat szerezhess.
</li>
<li>
<b>Virtuális Közösség:</b> Csatlakozz a játékosok közösségéhez, ahol megoszthatod NFT kártyáidat, cseveghetsz más trénerekkel, és részt vehetsz közösségi eseményeken.
</li>
</ul>
Az IKémon NFT egy korszerű kártyagyűjtő játék, ahol a blockchain technológia és az NFT-k együttesen hozzák létre a jövő virtuális kártyavilágát. Fedezd fel az IKémon NFT univerzumát, ahol az egyediség és az érték mindig a te kezedben van! Készülj fel, hogy a blockchain világában kiemelkedj, és a legkülönlegesebb IKémon-okat gyűjtsd össze!
    </p>


    <h3>Az összegyűjthető IKémonok:</h3>
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
    <div id="card-list">
                <?php $marketcounter=0;
                 foreach ($pkmns as $id => $pkmn) : ?>
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
                            <div class="buy">
                                💰<?= $pkmn["cost"]?>
                            </div>
                    </div>
                <?php endforeach ?>
    </div>
    </div>
    </body>
</html>