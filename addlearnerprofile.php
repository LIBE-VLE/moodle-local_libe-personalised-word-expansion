<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


global $DB;
global $USER;

// Require config.php file for database connection								// 1
require_once('/var/www/html/moodle/config.php');

// Create connection - MySQLi (object-oriented)									// 2
$conn = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

// Check connection - MySQLi (object-oriented)									// 3
if ($conn->connect_error) {
   die("Connection to the database failed: " . $conn->connect_error . "<br>");
}

// Set character set to UTF8													// 4
mysqli_set_charset($conn,"utf8");

if ($USER->id != 0) {
    
    // Select Statement - MySQLi (object-oriented)								// 5
    $sql = "SELECT * FROM mdl_libe-personalised-word-expansion_learner_profile WHERE userid = $USER->id";
    $result = $conn->query($sql);
    $row = $result->num_rows;

    // Insert rows in learner profile table										// 6
    if ($row == 0) {
	    // insert values into table mdl_libe-personalised-word-expansion_learner_profile
	    $sql = "INSERT INTO mdl_libe-personalised-word-expansion_learner_profile (userid, cognitivestyleid, completedinductiontest, lastupdatedbylibe) VALUES ($USER->id, 1, DEFAULT, NULL)";
	    $conn->query($sql);
    }
    
    // free result set															// 7
	$result->free();
}

// Close database connection													// 8
$conn->close();
