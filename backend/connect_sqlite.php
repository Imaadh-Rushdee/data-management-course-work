<?php
// connect_sqlite.php

// Path to your SQLite database file
try {
    $dbFile = __DIR__ . '/SQLite/finance_management_system.db';

    try {
        $sqlite_conn = new SQLite3($dbFile);
    } catch (Exception $e) {
        die("SQLite Connection failed: " . $e->getMessage());
    }

    // Enable foreign keys if needed
    $sqlite_conn->exec('PRAGMA foreign_keys = ON;');
    return true;
} catch (Exception $e) {
    return false;
}
?>