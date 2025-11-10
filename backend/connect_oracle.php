<?php
try{
$username = 'System';           
$password = '123';    
$connection_string = 'localhost:1521/XE'; 


$conn = @oci_connect($username, $password, $connection_string);

if (!$conn) {
    $e = oci_error();
    die("Oracle Connection Failed: " . $e['message']);
}


oci_close($conn);
return true;
}
catch(Exception $e){
    return false;
}
?>
