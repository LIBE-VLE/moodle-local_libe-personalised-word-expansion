<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


global $DB;
global $USER;

require_once('/var/www/html/moodle/config.php');

$word = $_GET['word'];

// Create connection - MySQLi (object-oriented)
$conn = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

// Check connection - MySQLi (object-oriented)
if ($conn->connect_error) {
   die("Connection to the database failed: " . $conn->connect_error . "<br>");
}

// Set character set to UTF8
mysqli_set_charset($conn,"utf8");

$sql = "SELECT description FROM mdl_libe-personalised-word-expansion_glossary WHERE word = '$word' AND expansionlevel = (SELECT abilitylevel FROM mdl_libe-personalised-word-expansion_user_ability_levels WHERE libethemeid = (SELECT id FROM mdl_libe-personalised-word-expansion_libe_themes WHERE libetheme = 'literacy') AND learnerprofileid = (SELECT id FROM mdl_libe-personalised-word-expansion_learner_profile WHERE userid = $USER->id))";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
   // output data of each row
   while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
      $outp .= '{"wordexpansion":"' . $rs["description"] . '"}';
   }
} else {
    $outp .= '{"wordexpansion":"' . $word . '"}';
}

// free result set
$result->free();

// close connection
$conn->close();

// output
echo $outp;
