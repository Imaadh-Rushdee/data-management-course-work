<?php
try {
    include('connect_sqlite.php');
    include('connect_oracle.php');

    if (!file_exists(__DIR__ . '/backups'))
        mkdir(__DIR__ . '/backups', 0777, true);

    $sqliteBackup = __DIR__ . '/backups/sqlite_backup_' . date('Ymd_His') . '.db';
    copy($dbFile, $sqliteBackup);

    $tables = ['expenses', 'budgets', 'savings'];
    foreach ($tables as $table) {
        $backupFile = __DIR__ . '/backups/' . $table . '_backup_' . date('Ymd_His') . '.csv';
        $fp = fopen($backupFile, 'w');
        $stmt = oci_parse($conn, "SELECT * FROM $table");
        oci_execute($stmt);
        $header = [];
        $ncols = oci_num_fields($stmt);
        for ($i = 1; $i <= $ncols; $i++)
            $header[] = oci_field_name($stmt, $i);
        fputcsv($fp, $header);
        while ($row = oci_fetch_assoc($stmt))
            fputcsv($fp, $row);
        fclose($fp);
    }

    oci_close($conn);
    echo "Backup completed.\n";

    return true;
} catch (Exception $e) {
    return false;
}

?>