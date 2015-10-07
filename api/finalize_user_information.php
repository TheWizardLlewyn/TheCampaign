<?php
/**
 * Created by PhpStorm.
 * User: nickr_000
 * Date: 10/6/2015
 * Time: 10:10 PM
 */

require("../includes.php");
if (!isset($user)) {
    die("No User - cannot finalize");
}

//save the party
$user->setParty($_POST['party']);

//save the job
$user->setJob($_POST['job_id']);

//save stats
$user->updateStats($_POST['stats']);

$_SESSION['user'] = $user;
?>