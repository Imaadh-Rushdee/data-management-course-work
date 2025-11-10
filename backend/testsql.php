<?php
// Path to your SQLite DB file
$dbFile = "C:\\Users\\VICTUS\\Desktop\\New folder\\HNDSE 25.1 F\\7. DM2 -  CW\\DMW CW\\SQLite\\finance_management_system.db";

try {
    // Open the SQLite database
    $sqlite_conn = new SQLite3($dbFile);

    // Test query to check connection
    $result = $sqlite_conn->query("SELECT name FROM sqlite_master WHERE type='table';");

    echo "Connection successful! Tables in database:<br>";
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo $row['name'] . "<br>";
    }

} catch (Exception $e) {
    echo "Failed to connect to SQLite: " . $e->getMessage();
}
?>
