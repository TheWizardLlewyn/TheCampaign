<?php
/**
 * This is the User object.  It holds all the data about
 * the user as well as all functions related to the user.
 */

class User {
    var $strength = 0;
    var $creativity = 0;
    var $stealth = 0;
    var $integrity = 0;
    var $investigation = 0;
    var $charm = 0;
    var $fundraising = 0;
    var $intimidation = 0;
    var $manipulation = 0;
    var $job = "";
    var $party = "";
    var $name = "";
    var $email = "";
    var $user_id;
    var $vehicle;
    var $personal_items;

    function __construct($user_id) {
        global $db;
        $this->user_id = $user_id;
        $user_info = $db->query_one("SELECT * FROM users WHERE user_id = {$user_id}");
        $this->strength = $user_info['strength'];
        $this->creativity = $user_info['creativity'];
        $this->stealth = $user_info['stealth'];
        $this->integrity = $user_info['integrity'];
        $this->investigation = $user_info['investigation'];
        $this->charm = $user_info['charm'];
        $this->fundraising = $user_info['fundraising'];
        $this->intimidation = $user_info['intimidation'];
        $this->manipulation = $user_info['manipulation'];
        $this->email = $user_info['email'];
        $this->name = $user_info['name'];
        $this->party = $user_info['party_id'];
        //TODO: Load job
        //TODO: LOAD PARTY
        //TODO: Load Personal Items
        //TODO: Load Vehicle
    }

    public static function createUser(
        $email,
        $password,
        $name,
        $strength,
        $creativity,
        $stealth,
        $integrity,
        $investigation,
        $charm,
        $fundraising,
        $intimidation,
        $manipulation,
        $job_id,
        $party_id
        ) {
        global $db, $config;
        //TODO: check that email is valid
        //Check that email is unique
        $check = $db->query_one("SELECT user_id FROM users WHERE email = x'".bin2hex($email)."'");
        if (isset($check['user_id'])) {
            return false;
        }

        $hashed_password = md5($password.$config['hash']);

        //email is unique - create a new user:
        $user_id = $db->query("INSERT INTO users
            (email, password, name, strength, creativity, stealth,
            integrity, investigation, charm, fundraising, intimidation,
            manipulation, job_id, party_id)
          VALUES
          (x'".bin2hex($email)."','{$hashed_password}',x'".bin2hex($name)."',
          {$strength}, {$creativity},{$stealth},{$integrity},{$investigation},
          {$charm},{$fundraising},{$intimidation}, {$manipulation},{$job_id},{$party_id}) ");

        return new User($user_id);

    }
}

?>

