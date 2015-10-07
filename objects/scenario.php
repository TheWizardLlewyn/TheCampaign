<?php
//The scenario object handles creation and resolution of scenarios

class Scenario {
    var $user_id;
    var $job_id;
    var $description;
    var $name;
    var $scenario_status;
    var $scenario_date;
    var $options;

    function __construct($scenario_id) {
        global $db;
        $scenario_info = $db->query_one("SELECT * FROM scenarios WHERE scenario_id = {$scenario_id}");
        $this->user_id = $scenario_info['user_id'];
        $this->job_id = $scenario_info['job_id'];
        $this->description = $scenario_info['description'];
        $this->name = $scenario_info['name'];
        $this->scenario_status = $scenario_info['status'];
        $this->scenario_date = $scenario_info['date'];

        //load the options
        $this->options = $db->query("SELECT * FROM scenario_options WHERE scenario_id = {$scenario_id}");
    }

}