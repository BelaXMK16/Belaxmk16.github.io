<?php
function validate(&$data, &$errors)
{
    $data = $_POST;

    if (!isset($_POST["pkmn"]) || trim($_POST["pkmn"] === "")) {
        $errors["pkmn"] = "Pokémon megadása kötelező!";
    }
    if (!isset($_POST["hp"]) || trim($_POST["hp"] === "")) {
        $errors["hp"] = "Életerő megadása kötelező!";
    }
    if (!isset($_POST["atk"]) || trim($_POST["atk"] === "")) {
        $errors["atk"] = "Támadás megadása kötelező!";
    }
    if (!isset($_POST["def"]) || trim($_POST["def"] === "")) {
        $errors["def"] = "Védelem megadása kötelező!";
    }
    if (!isset($_POST["type"]) || trim($_POST["type"]) === "") {
        $errors["type"] = "Típus megadása kötelező!";}
    if (!isset($_POST["dex_num"]) || trim($_POST["dex_num"] === "")) {
        $errors["dex_num"] = "Pokédex-Szám megadása kötelező!";
    }
    if (!isset($_POST["cost"]) || trim($_POST["cost"] === "")) {
        $errors["cost"] = "Kártya érték megadása kötelező!";
    }
    return count($errors) == 0;

}
