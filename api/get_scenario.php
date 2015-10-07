<?php
/**
 * Created by PhpStorm.
 * User: nickr_000
 * Date: 10/6/2015
 * Time: 10:10 PM
 */
require("../includes.php");

$scenario = null;
//Check the database for an existing uncompleted scenario for this user
$scenario_check = $db->query_one("SELECT scenario_id FROM scenarios WHERE user_id = {$user->user_id} AND status = " . SCENARIO_STATUS::AVAILABLE);
if (isset($scenario_check['scenario_id'])) {
    $scenario = new Scenario($scenario_check['scenario_id']);
} else {
    //if no uncompleted scenarios - see if the user has already completed a scenario for the day
    $scenario_check = $db->query_one("SELECT scenario_id FROM scenarios WHERE user_id = {$user->user_id} AND status = " . SCENARIO_STATUS::COMPLETED . " AND scenario_date = date(today())");
    if (isset($scenario_check['scenario_id'])) {
        die("<h2>No new scenarios for today</h2>");
    }
}

//if completely new user - or they simply didn't get a scenario - create a scenario for them
if ($scenario == null) {
    $scenario = Scenario::createNewScenario($user);
}

//process and display the scenario


?>