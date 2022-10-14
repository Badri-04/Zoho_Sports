<?php

	$con = mysqli_connect("localhost:3307","root","");
	$query = "CREATE DATABASE zoho_sports";
	try{
		mysqli_query($con,$query);
		mysqli_close($con);
	}
	catch(Exception $e){
		echo "Database alraedy existed";
	}

	require "Access_Functions/connect.php";
	require "Access_Functions/db_access.php";

	$users = "CREATE TABLE users(
		user_id INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(50),
		email VARCHAR(50),
		age INT,
		address VARCHAR(300),
		passwords VARCHAR(50),
		team_id INT,
		blood_group VARCHAR(10),
		PRIMARY KEY(user_id)
	)";

	$tours = "CREATE TABLE tournaments(
		tournament_id INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(50),
		sport VARCHAR(30),
		max_participation INT,
		type VARCHAR(10),
		start_date VARCHAR(10),
		prize INT,
		duration INT,
		reg_status VARCHAR(10) DEFAULT 'Open',
		PRIMARY KEY(tournament_id)
	)";

	$teams = "CREATE TABLE teams(
		team_id INT NOT NULL AUTO_INCREMENT,
		team_name VARCHAR(50),
		leader_id INT NOT NULL UNIQUE,
		PRIMARY KEY(team_id),
		FOREIGN KEY(leader_id) REFERENCES users(user_id) ON DELETE CASCADE
	)";

	$partic = "CREATE TABLE participants(
		tournament_id INT,
		participant_id INT,
		status VARCHAR(8),
		FOREIGN KEY(tournament_id) REFERENCES tournaments(tournament_id) ON DELETE CASCADE
	)";

	$matches = "CREATE TABLE matches(
		match_id INT NOT NULL AUTO_INCREMENT,
	    tournament_id INT NOT NULL,
	    participant_id1 INT NOT NULL,
	    participant_id2 INT NOT NULL,
	    winner_id INT NOT NULL DEFAULT 0,
	    PRIMARY KEY(match_id),
	    FOREIGN KEY(tournament_id) REFERENCES tournaments(tournament_id) ON DELETE CASCADE 
	)";

	$tm = "CREATE TABLE team_members(
			team_id int,
		    user_id int,
		    FOREIGN KEY(team_id) REFERENCES teams(team_id) ON DELETE CASCADE,
		    FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
		)";

	CreateTable($conn,$users);
	CreateTable($conn,$tours);
	CreateTable($conn,$teams);
	CreateTable($conn,$partic);
	CreateTable($conn,$matches);
	CreateTable($conn,$tm);
?>