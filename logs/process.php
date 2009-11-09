<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db_vars.php';
//error_reporting(E_ALL);

if($_REQUEST['entries'] == "listall") {

    $stmt = $conn->prepare("SELECT LogId,Event,EventDate from logevent order by EventDate desc limit 0,30");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($LogId,$Event,$EventDate);
        ?>
<h2>Last 30 entries</h2>
<table id="logstable" border="1">
    <tr>

        <th>LogId</th>
        <th>Event</th>
        <th>Date</th>
  
    </tr>


            <?php
            
            while ($stmt->fetch()) {
                echo "<tr>";

                        echo "<td>".$LogId."</td>";
                        echo "<td>".$Event."</td>";
                        echo "<td>".$EventDate."</td>";
                     

                echo "</tr>";
            }
            ?>
</table>
    <?php


    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}
?>
