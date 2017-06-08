<?php

$NewUser = false;

if(!isset($_SESSION['UID'])){
	$sth = $conn->prepare("SELECT ID, Role FROM Users where Steam64 = :Steam64");
	$sth->execute(array(':Steam64' => $steamprofile['steamid']));
	$User = $sth->fetchAll();

	if ($User == NULL){
		$sth = $conn->prepare("INSERT INTO Users (Steam64, Avatar) VALUES (:Steam64, :Avatar);");
		$sth->bindParam(':Steam64', $steamprofile['steamid']);
		$sth->bindParam(':Avatar', $steamprofile['avatarfull']);
		$sth->execute();

		$sth = $conn->prepare("SELECT ID, Role FROM Users where steam64 = :steam64");
		$sth->execute(array(':steam64' => $steamprofile['steamid']));
		$User = $sth->fetchAll();

		$NewUser = true;
	}else{
		$sth = $conn->prepare("UPDATE Users SET Avatar = :Avatar WHERE Steam64 = :Steam64;");
		$sth->bindParam(':Avatar', $steamprofile['avatarfull']);
		$sth->bindParam(':Steam64', $steamprofile['steamid']);
		$sth->execute();
	}

	$_SESSION['UID'] = $User[0][0];
    $_SESSION['Role'] = $User[0][1];

	$sth = $conn->prepare("INSERT INTO LoginHistory (UserID, IP) VALUES (:UID, :IP);");
	$sth->bindParam(':UID', $_SESSION['UID']);
	$sth->bindParam(':IP', $_SERVER['REMOTE_ADDR']);
	$sth->execute();

	$Hash = (rand(0,1) == 1) ? 'sha512' : 'whirlpool';
	$_SESSION['PrivateKey'] = hash($Hash, rand() . $steamprofile['steamid'] . rand() . $steamprofile['communityvisibilitystate'] . rand() . $steamprofile['personaname'] . rand() . $steamprofile['profileurl'] . rand() . $steamprofile['avatarfull'] . rand());

	$sth = $conn->prepare("UPDATE Users SET PrivateKey = :PrivateKey WHERE ID = :UID");
	$sth->bindParam(':UID', $_SESSION['UID']);
	$sth->bindParam(':PrivateKey', $_SESSION['PrivateKey']);
	$sth->execute();
}