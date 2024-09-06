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

if (count($_GET) > 0) {
    if (isset($_GET["id"]) && trim($_GET["id"]) !== '') {

        $id = $_GET["id"];
        $pkmnStorage = new pkmnStorage();
        $pkmndata=$pkmnStorage->findById($id);
        $pkmndata["userId"]="0";
        $pkmnStorage->update($id, $pkmndata);
        $user['money']=$user['money']+$pkmndata['cost']*0.9;
        $userStorage->update($user["id"],$user);
        $auth->login($user);
    }
}
header("Location: index.php");
exit();