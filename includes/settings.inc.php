<?php

define('SHINJUKU_DB_CONN', 'mysql:unix_socket=/tmp/mysql.sock;dbname=laika');

/**
 * PDO database login credentials
 */

/** @param string POMF_DB_NAME Database username */
define('SHINJUKU_DB_USER', 'laika');
/** @param string POMF_DB_PASS Database password */
define('SHINJUKU_DB_PASS', '***');

/**
 * 'LAIKA_FILES_ROOT' - Where to store the files
 * 'LENGTH' - Invite key length
 * 'LAIKA_NAME' - Laika instance name
 * 'LAIKA_ADDRESS' - Laika address/[sub]domain
 * 'LAIKA_URL' - URL/[sub]domain to host files from
 * 'SHINJUKU_URL' - URL for shinjuku
 * 'ID_CHARSET' - set of characters to use for file IDs
 */
define('LAIKA_FILES_ROOT', '');
define('LENGTH', 32);
define('LAIKA_NAME', 'Laika');
define('LAIKA_ADDRESS', 'laika.rf.gd');
define('LAIKA_URL', 'http://laika.rf.gd/');
define('SHINJUKU_URL', 'http://laika.rf.gd/shinjuku');
define('ID_CHARSET', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

/** SMTP email settings */
define('SMTPD_HOST', '');
define('SMTPD_USERNAME', '');
define('SMTPD_PASSWORD', '');

/** Cloudflare creds for removing deleted files from their cache */
define('CF_EMAIL', '');
define('CF_TOKEN', '');
