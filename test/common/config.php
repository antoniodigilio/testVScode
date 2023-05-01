<?php
// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);
//base url
$link = "https://test.promomedia.online/PortaleCocaCola/test/";
$path = dirname(__FILE__).'/../';
define("BASE_URL", "https://test.promomedia.online/PortaleCocaCola/test/");
//DB
//main DB config connection
define("DB_HOST", "localhost");
define("DB_NAME", "cocaCola_db");
define("DB_USER", "cocacoladb");
define("DB_PASS", "Tf5i2n%2");
define("HASH_COST_FACTOR", "10");
define("PASSWORD_EXPIRATION_GG",90);

define("EMAIL_PASSWORDRESET_SUBJECT", "Reimposta la tua password");
define("EMAIL_PASSWORDRESET_CONTENT", "Clicca qui per reimpostare la tua password:");
define("EMAIL_PASSWORDRESET_URL", BASE_URL . "common/password_reset.php");
?>