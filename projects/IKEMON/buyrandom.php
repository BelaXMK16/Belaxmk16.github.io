<?php 
include("pkmnstorage.php");
include("userstorage.php");
include("auth.php");

session_start();

$userStorage = new UserStorage();
$auth = new Auth($userStorage);
$pkmnStorage = new pkmnStorage();
$userpkmncounter=0;
$user=NULL;
if ($auth->is_authenticated()) {
    $user = $auth->authenticated_user();
} else {
    header("Location: login.php");
}
echo($user["id"]);
$filter="";
$pkmns = $pkmnStorage->findMany(function ($pkmn) use ($filter) {

    return (str_contains($pkmn['pkmn'], $filter));
});
foreach ($pkmns as $key => $pkmn) {
    if ($pkmn['userId']==$user["id"]){
        $userpkmncounter++;
    }
}
echo($user["id"]);
$randindex=rand(0,count($pkmns)-1);
$counter=0;
foreach ($pkmns as $key => $pkmn) {
    
    if($counter==$randindex){
        $id=$pkmn["id"];
        break;
    }
    $counter+=1;
}
$pkmndata=$pkmnStorage->findById($id);

if ($user['money']>=500) {
    if($userpkmncounter<5){
    $pkmndata["userId"]=$user["id"];
    $pkmnStorage->update($id, $pkmndata);
    $user['money']=$user['money']-500;
    $auth->login($user);
    }
    
}
$asd="Location: market.php?asd=".$userpkmncounter;
header($asd);
exit();
?>