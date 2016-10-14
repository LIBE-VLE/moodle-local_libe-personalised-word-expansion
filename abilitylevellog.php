<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $DB;
global $USER;

// Require config.php file for database connection								// 1
require_once('/var/www/html/moodle/config.php');

// Get quiz number																// 2
$quiz = $_GET['quiz'];

// Create connection - MySQLi (object-oriented)									// 3
$conn = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

// Check connection - MySQLi (object-oriented)									// 4
if ($conn->connect_error) {
   die("Connection to the database failed: " . $conn->connect_error . "<br>");
}

// Set character set to UTF8													// 5
mysqli_set_charset($conn,"utf8");

// Check if user is connected													// 6
if ($USER->id != 0) {

	// Select Statement - MySQLi (object-oriented)								// 7
    $sql = "SELECT id FROM mdl_libe-personalised-word-expansion_learner_profile WHERE userid = $USER->id" ;
    $result = $conn->query($sql);
	
	// Get learner profile id													// 8
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$learnerprofileid = $row['id'];
	}
	
	// free result set															// 9
    $result->free();

    // Select Statement - MySQLi (object-oriented)								// 10
    $sql = "SELECT abilitylevel FROM mdl_libe-personalised-word-expansion_user_ability_levels WHERE learnerprofileid = $learnerprofileid AND libethemeid = 2" ;
    $result = $conn->query($sql);
    
   	// Get ability level
   	if ($result->num_rows > 0) {
    	$row = $result->fetch_assoc();
		$abilitylevel = $row['abilitylevel'];

		// free result set														// 11
		$result->free();
		
    	// Insert rows in mdl_libe-personalised-word-expansion_ability_level_log table						// 12
		$sql = "INSERT INTO mdl_libe-personalised-word-expansion_ability_level_log (learnerprofileid, libethemeid, quiz, abilitylevel, timelogged) VALUES ($learnerprofileid, 2, $quiz, '$abilitylevel', NULL)";
		$conn->query($sql);
	}
}

// Close database connection													// 13
$conn->close();
