<?php

//DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'fitmod_db');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root
define('URLROOT', 'http://localhost/fitmod');
// Site Name
define('SITENAME', 'FITMOD');
// Set Max Exercises per Workout Mod
define('EXERCISEMAX', 15);
// Set Timezone
date_default_timezone_set("America/Los_Angeles");
