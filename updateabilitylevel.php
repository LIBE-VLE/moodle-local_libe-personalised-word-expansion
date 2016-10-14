<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

global $DB;
global $USER;
global $abilitylevel;

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
    $sql = "SELECT grade FROM mdl_quiz_grades ".
    	   "WHERE userid=$USER->id AND quiz=$quiz";
    $result = $conn->query($sql);
	
	// Check if any row is returned												// 8
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$grade = $row['grade'];
		
		// Map score to ability level											// 9
		if ($grade <= 3.33330) {
			$abilitylevel='low';
		} elseif ($grade > 3.33330 && $grade <= 6.66660) {
			$abilitylevel='medium';
		} else {
			$abilitylevel='high';
		}
	}

	// free result set															// 10
    $result->free();

	// Select Statement - MySQLi (object-oriented)								// 11
    $sql = "SELECT id FROM mdl_libe-personalised-word-expansion_learner_profile WHERE userid=$USER->id";
    $result = $conn->query($sql);
	
	// Get learner profile id													// 12
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$learnerprofileid = $row['id'];
	}
	
	// free result set															// 13
    $result->free();

	// Select Statement - MySQLi (object-oriented)								// 14
    $sql = "SELECT abilitylevel FROM mdl_libe-personalised-word-expansion_user_ability_levels ".
     	   "WHERE learnerprofileid=$learnerprofileid AND libethemeid=2";
    $result = $conn->query($sql);
    $rows = $result->num_rows;

	// free result set															// 15
	$result->free();
		
    // Insert or update rows in mdl_libe-personalised-word-expansion_user_ability_levels table				// 16
    if ($rows > 0) {
	    
	    // update values of table mdl_pe_user_ability_levels
	    $sql = "UPDATE mdl_libe-personalised-word-expansion_user_ability_levels ".
	    	   "SET abilitylevel='$abilitylevel', lastupdated=CURRENT_TIMESTAMP ".
	    	   "WHERE learnerprofileid=$learnerprofileid AND libethemeid=2";
	    $conn->query($sql);
    } else {
	    // insert values into table mdl_libe-personalised-word-expansion_user_ability_levels
	    $sql = "INSERT INTO mdl_libe-personalised-word-expansion_user_ability_levels ".
	    	   "(learnerprofileid, libethemeid, abilitylevel, lastupdated) ".
	    	   "VALUES ($learnerprofileid, 2, '$abilitylevel', NULL)";
	    $conn->query($sql);
    }
    
    // Insert row in mdl_libe-personalised-word-expansion_ability_level_log table						// 17
	$sql = "INSERT INTO mdl_libe-personalised-word-expansion_ability_level_log ".
		   "(learnerprofileid, libethemeid, quiz, abilitylevel, timelogged) ".
		   "VALUES ($learnerprofileid, 2, $quiz, '$abilitylevel', NULL)";
	$conn->query($sql);
}

// Close database connection													// 17
$conn->close();
