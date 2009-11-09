<?php
/**
 * Performas validation and database functionality
 * 
 * @author m.jinahadam
 */
require_once '../config/db_vars.php';


if($_REQUEST["function"] == "update") {
        $id = $_REQUEST['id'];


        $stmt = $conn->prepare("update useraccount set Email = ?, Type = ? where UserId = ?");
    if ($stmt) {
        $stmt->bind_param('ssd',$_REQUEST['email'],$_REQUEST['usertype'], $id);
        $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "User with id  ".$_POST['id']." was updated.";
    }
  }
}



if($_REQUEST["function"] == "userdetails") {
        $id = $_REQUEST['id'];


        $stmt = $conn->prepare("SELECT UserId,UserName,Password,Email,Type from useraccount where UserId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($UserId, $UserName,$Password,$Email,$Type);
      
            while ($stmt->fetch()) {
                 echo "[ {email: '".$Email."', username: '".$UserName."'}]";
               
            }
          
    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }
   
}



if($_REQUEST['function'] == "delete") {

    $stmt = $conn->prepare("delete from useraccount where UserId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
         if ($stmt->affected_rows > 0) {
                echo "User with id  ".$_POST['id']." was deleted.";
            }
    }
}

if($_REQUEST['function'] == "listall") {

    $stmt = $conn->prepare("SELECT UserId,UserName,Password,Email,Type from useraccount");
    if ($stmt) {       
        $stmt->execute();
        $stmt->bind_result($UserId, $UserName,$Password,$Email,$Type);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Email</th>
        <th>User Previledge</th>
        <th>Action </th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";
                        echo "<td>".$UserId."</td";
                        echo "<td>".$UserName."</td";
                        echo "<td>".$Email."</td";
                        switch($Type) {
                            case 0:
                                $typestring = "Administrator";
                                break;
                            case 1:
                                $typestring = "Carpet user";
                                break;
                            case 0:
                                $typestring = "Normal user";
                                break;
                        }
                        echo "<td>".$typestring."</td>";
                        ?>
                        <td><a href="#" id="delete" OnClick="deleteuser(<?php echo $UserId;?>);"> Delete </a>| 
                            <a href="#" id="edit" OnClick="edituser(<?php echo $UserId;?>);"> Edit </a> </td>
                        <?php

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

if($_POST) {
    if($_POST['username'] != "" && $_POST['password'] != "" && $_POST['email'] != "") {
        $isValidate = true;

        if($isValidate == true) {

        //everything is ok.. add user to the database

            $stmt = $conn->prepare("INSERT INTO useraccount(UserName,Password,Type,Email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $username, $password, $usertype, $email);

            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $usertype = $_POST['usertype'];
            $email = $_POST['email'];

            /* execute prepared statement */
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "true";
            }
        }else {
        //echo '{"jsonValidateReturn":'.json_encode($arrayError).'}';		// RETURN ARRAY WITH ERROR
        }



    }
    else {
    // echo "Required fields missing, user not added.";
    }
}

?>
