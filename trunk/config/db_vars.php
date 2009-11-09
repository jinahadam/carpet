<?php
/**
 * Description of db_vars
 * Connects to the database,
 * @author m.jinahadam
 *
 * @@todo get db variables form congig file
 */
$conn = mysqli_connect("localhost", "root", "", "carpet_web");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


?>
