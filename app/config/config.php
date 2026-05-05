<?php
// DB Params - Use environment variables for production (Vercel/Supabase)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3307');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'restaurant_db');
define('DB_DRIVER', getenv('DB_DRIVER') ?: 'mysql'); // 'mysql' for local, 'pgsql' for Supabase

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root - Use environment variable for production URL
define('URLROOT', getenv('URLROOT') ?: 'http://localhost/restaurant2');
// Site Name
define('SITENAME', "Bhoomi's Kitchen Management System");
