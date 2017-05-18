<?php

if(!isset($_SESSION['UID'])){
	$sth = $conn->prepare("SELECT ID, Role FROM Users where Steam64 = :Steam64");
	$sth->execute(array(':Steam64' => $steamprofile['steamid']));
	$User = $sth->fetchAll();

    $NewUser = false;

	if ($User == NULL){
		$sth = $conn->prepare("INSERT INTO Users (Steam64) VALUES (:Steam64);");
		$sth->bindParam(':Steam64', $steamprofile['steamid']);
		$sth->execute();

		$sth = $conn->prepare("SELECT ID, Role FROM Users where steam64 = :steam64");
		$sth->execute(array(':steam64' => $steamprofile['steamid']));
		$User = $sth->fetchAll();

		$NewUser = true;
	}

    $_SESSION['UID'] = $User[0][0];
    $_SESSION['Role'] = $User[0][1];
}
