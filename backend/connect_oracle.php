<?php

/**
 * connect_oracle.php
 * Test connection to Oracle database using OCI8
 */

// Oracle credentials
$username = 'system';           // Replace with your Oracle username
$password = 'Savishka4';    // Replace with your Oracle password
$connection_string = 'localhost:1521/XE'; // Replace XE if your service name is different

// Attempt to connect
$conn = @oci_connect($username, $password, $connection_string);

if (!$conn) {
    $e = oci_error();
    die("Oracle Connection Failed: " . $e['message']);
}

echo "Connected to Oracle successfully!";

// Close connection
oci_close($conn);
