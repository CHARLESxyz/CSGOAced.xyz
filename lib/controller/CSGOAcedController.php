<?php
session_start();

require_once ("lib/controller/Config.php");

if (isset($_GET['r'])){ 
	$_SESSION['referal'] = strtolower($_GET['r']);
	header('Location: ' . $Link->Website);
	die();
}

if (isset($_GET['logout'])){
	require_once ('lib/database/Connect.php');
	require_once ('lib/database/Logout.php');
	require_once ("lib/SteamAuth/steamauth/steamauth.php");
}

require_once ("lib/SteamAuth/steamauth/steamauth.php");

$GetPage = $Page->Home;
if (isset($_GET['p'])){ $GetPage = $_GET['p']; }

if(isset($_SESSION['steamid'])) {
	require_once ('lib/SteamAuth/steamauth/userInfo.php');
	require_once ('lib/database/Connect.php');
	require_once ('lib/database/RegisterUser.php');

	if ($NewUser || isset($_GET[$Page->UpdateInventory])){
		require_once ('lib/database/UpdateInventory.php');
	}
}