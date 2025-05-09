<?php
/**
 * Configuration file for API database connection.
 *
 * This file returns an associative array containing the database connection
 * details required for the application to connect to the database.
 *
 * Keys:
 * - 'servername': The hostname or IP address of the database server.
 * - 'username': The username used to authenticate with the database.
 * - 'password': The password associated with the database username.
 * - 'dbname': The name of the database to connect to.
 *
 * Example usage:
 * ```php
 * $config = include('/path/to/config/api.php');
 * $servername = $config['servername'];
 * $username = $config['username'];
 * $password = $config['password'];
 * $dbname = $config['dbname'];
 * ```
 *
 * Note: Ensure this file is not publicly accessible to prevent exposure
 * of sensitive credentials.
 */
return [
    'servername' => 'localhost',
    'username' => 'root',
    'password' => 'jdih_T@n99uH@@#!',
    'dbname' => 'jdih_bnpb_fix',
];