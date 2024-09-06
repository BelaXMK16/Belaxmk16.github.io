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
    if (isset($_GET["id"]) && trim($_GET["id"]) !== '' ) {

        $id = $_GET["id"];
        $pkmnStorage = new pkmnStorage();

        $pkmns = $pkmnStorage->findMany(function($pkmn) use ($user) {
            return $pkmn;
        });
        $usercount=0 ;
        $pkmndata=$pkmnStorage->findById($id);
        foreach ($pkmns as $key => $pkmn){
            if($pkmn["userId"]==$user["id"]){
                $usercount+=1;
            }
        }
        if($usercount<5 && $user['money']-$pkmndata['cost']>=0){  

            $pkmndata["userId"]=$user["id"];
            $pkmnStorage->update($id, $pkmndata);
            $user['money']=$user['money']-$pkmndata['cost'];
            $userStorage->update($user["id"],$user);
            $auth->login($user);
        }
    }
}
header("Location: market.php");
exit();