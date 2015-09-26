<pre>
<?php
require("includes.php");

//$user = User::createUser("llewyn@thewizardllewyn.com","hahaha","Llewyn",1,1,1,1,1,1,1,1,1,2,2);
$user = new User(1);
echo var_export($user,true);
//echo "This is a test: " . $user->email . " " . $user->name . "\n";

//echo "This is a test: {$user->email} {$user->name}\n";

?>
</pre>