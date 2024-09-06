<?php
include("pkmnstorage.php");

$pkmn = [];

if (isset($_GET["id"]) && trim($_GET["id"]) !== "") {
    $id=$_GET["id"];
    
    $pkmnStorage = new PkmnStorage();
    $pkmn = $pkmnStorage->findById($id);
}
?>

<!DOCTYPE html>
<html lang="en">
<title>IKémon <?=$pkmn["pkmn"]?></title>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <html lang="en">
    <link rel="stylesheet" type="text/css" href="styles/cards.css" />
    <link rel="stylesheet" type="text/css" href="styles/details.css" />
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
</head>

<body>
    <header>
        <h1><a href="index.php">IKémon</a> > <?=$pkmn["pkmn"]?></h1>
    </header>
    <div id="content">
        <div id="details">
            <div class="image clr-<?=$pkmn["type"]?>">
                <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/<?=str_pad($pkmn["dex_num"],3,"0",STR_PAD_LEFT);?>.png" alt="">
            </div>
            <div class="info">
                <div class="description">
                    <?=$pkmn["desc"];?> </div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?=$pkmn["type"]?></span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?=$pkmn["hp"]?></div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?=$pkmn["atk"]?></div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?=$pkmn["def"]?></div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>