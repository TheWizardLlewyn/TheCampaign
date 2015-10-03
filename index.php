<?php
include("includes.php");
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="campaign.css"/>
        <script src="js/campaign.js"></script>
        <title>The Campaign</title>
    </head>
    <body>
        <div id="gameboard">
            <div id="game_heading">Welcome to The Campaign</div>
            <div id="left_side">
                <h2>Create New Account</h2>
                <input id="signup_email" type="text" placeholder="Email" class="signup_input"/>
                <input id="signup_name" type="text" placeholder="Character Name" class="signup_input"/>
                <input id="signup_password" type="password" placeholder="Password" class="signup_input"/>
                <input id="agree_to_terms_of_service" type="checkbox"/> I agree to the <a href="termsofservice.html">Terms of Service</a>
                <button id="create_account_button">Create Account</button>

            </div>
            <div id="right_side">
                <h2>Sign In</h2>

            </div>

            <div id="full_width"></div>
            <div style="clear:both;"></div>
        </div>
    </body>
</html>

