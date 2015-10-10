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
    var $effected_party_id;
    var $target_name;
    var $severity;

    function __construct($scenario_id) {
        global $db;
        $scenario_info = $db->query_one("SELECT * FROM scenarios WHERE scenario_id = {$scenario_id}");
        $this->user_id = $scenario_info['user_id'];
        $this->job_id = $scenario_info['job_id'];
        $this->description = $scenario_info['description'];
        $this->name = $scenario_info['name'];
        $this->scenario_status = $scenario_info['status'];
        $this->scenario_date = $scenario_info['date'];
        $this->severity = $scenario_info['severity'];
        $this->effected_party_id= $scenario_info['effected_party_id'];
        $this->target_name= $scenario_info['target_name'];

        //load the options
        $this->options = $db->query("SELECT * FROM scenario_options WHERE scenario_id = {$scenario_id}");
    }

    /**
     * @param $user User
     */
    public static function createNewScenario($user) {
        global $db, $PARTY_IDS, $PARTY_NAMES;
        //Figure out what job the user has
        if ($user->job_id == JOB_IDS::REPORTER) {
            $effected_party_id = rand(1,count($PARTY_IDS));
            $is_users_party = ($effected_party_id == $user->party_id);

            $target_name = Scenario::generateRandomName();

            //Is the news good or bad?  And how bad?
            $severity_scale = rand(1,100); // 50 is neutral, 1 is terrible, 100 very good
            $random_event = null;
            $options = array();
            if ($severity_scale < 10) {

                //terrible event
                $random_event = $db->query_one("SELECT scenario_description, scenario_title FROM scenario_descriptions WHERE job_id = {$user->job_id} AND severity_scale = ". SEVERITY_SCALE::TERRIBLE . " ORDER BY RAND() LIMIT 1");

            } else if ($severity_scale <=50) {
                //mildly bad event
                $random_event = $db->query_one("SELECT scenario_description, scenario_title FROM scenario_descriptions WHERE job_id = {$user->job_id} AND severity_scale = ". SEVERITY_SCALE::MILDLY_BAD . " ORDER BY RAND() LIMIT 1");
            } else if ($severity_scale <=90) {
                //mildly good event
                $random_event = $db->query_one("SELECT scenario_description, scenario_title FROM scenario_descriptions WHERE job_id = {$user->job_id} AND severity_scale = ". SEVERITY_SCALE::MILDLY_GOOD . " ORDER BY RAND() LIMIT 1");
            } else {
                //amazing event.
                $random_event = $db->query_one("SELECT scenario_description, scenario_title FROM scenario_descriptions WHERE job_id = {$user->job_id} AND severity_scale = ". SEVERITY_SCALE::AMAZING . " ORDER BY RAND() LIMIT 1");
            }

            $options[0] = array("text"=>"Stretch the negatives");
            $options[1] = array("text"=>"Report just the facts");
            $options[2] = array("text"=>"Downplay/minimize with facts");
            $options[3] = array("text"=>"Stretch the positive angles");


            //add the NAME and PARTY into the event.
            // event: "[name] ([party]) was speeding and got out of a ticket because they are a politician.
            $scenario_description = str_replace(array("[name]","[party]"),array($target_name, $PARTY_IDS[$effected_party_id]), $random_event['scenario_description']);
            $scenario_title = str_replace(array("[name]","[party]"),array($target_name, $PARTY_IDS[$effected_party_id]), $random_event['scenario_title']);

            //save to database
            $scenario_id = $db->query("INSERT INTO scenarios
                    (user_id, job_id,
                    description, name,
                    scenario_status, scenario_date, severity,
                    target_name, effected_party_id)
                    VALUES
                    ({$user->user_id}, {$user->job_id}, x'".bin2hex($scenario_description)."', x'".bin2hex($scenario_title)."', 0, DATE(NOW()), $severity_scale, x'". bin2hex($target_name) . "', $effected_party_id)");

            //insert the options
            $option_inserts = array();
            foreach ($options as $option) {
                $empty_array = serialize(array());
                $option_inserts[] = "($scenario_id, x'".bin2hex($option['text'])."',x'".bin2hex($empty_array)."')";
            }

            $db->query("INSERT INTO scenario_options (scenario_id, scenario_option_description, scenario_option_result) VALUES ". implode(",",$option_inserts));
        }

        return new Scenario($scenario_id);


    }

    public function resolveScenario($option_selected) {
        global $db, $user;
        $selected_option_info = null;
        foreach ($this->options as $option) {
            if ($option['scenario_option_id'] == $option_selected) {
                $selected_option_info = $option;
            }
        }

        if (!isset($user)) {
            $user = new User($this->user_id);
        }

        if ($selected_option_info == null) {
            return "Invalid option selected.";
        }

        $is_effected_users_party = ($this->effected_party_id == $user->party_id);

        // Update integrity
        $integrity_change = 0;
        $user_following_change = 0;
        $target_party_following_change = 0;
        if ($is_effected_users_party) {
            if ($this->severity < 10) {
                // VERY bad event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -10;
                        break;
                    case "Report just the facts":
                        $integrity_change = 5;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 1;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -3;
                        break;
                }

            } else if ($this->severity <= 50) {
                // Mildly bad event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -2;
                        break;
                    case "Report just the facts":
                        $integrity_change = 2;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = -1;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -2;
                        break;
                }

            } else if ($this->severity <= 90) {
                // Mildly GOOD event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -10;
                        break;
                    case "Report just the facts":
                        $integrity_change = 0;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 0;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -1;
                        break;
                }

            } else {
                //VERY good event.
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -10;
                        break;
                    case "Report just the facts":
                        $integrity_change = 3;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 0;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -2;
                        break;
                }
            }
        } else {
            //if we are NOT affecting the user's party
            if ($this->severity < 10) {
                // VERY bad event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -3;
                        break;
                    case "Report just the facts":
                        $integrity_change = 3;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 1;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -3;
                        break;
                }

            } else if ($this->severity <= 50) {
                // Mildly bad event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -2;
                        break;
                    case "Report just the facts":
                        $integrity_change = 2;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = -1;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -2;
                        break;
                }

            } else if ($this->severity <= 90) {
                // Mildly GOOD event
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -3;
                        break;
                    case "Report just the facts":
                        $integrity_change = 0;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 0;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -1;
                        break;
                }

            } else {
                //VERY good event.
                switch ($selected_option_info['scenario_option_description']) {
                    case "Stretch the negatives":
                        $integrity_change = -10;
                        break;
                    case "Report just the facts":
                        $integrity_change = 4;
                        break;
                    case "Downplay/minimize with facts":
                        $integrity_change = 0;
                        break;
                    case "Stretch the positive angles":
                        $integrity_change = -2;
                        break;
                }

            }
        }


        // Update effected party followers
        // update individual's followers


    }


    public static function generateRandomName() {
        global $FIRST_NAMES, $LAST_NAMES;
        //first names

        return $FIRST_NAMES[rand(0,count($FIRST_NAMES)-1)] . " " . $LAST_NAMES[rand(0,count($LAST_NAMES)-1)];
    }
}