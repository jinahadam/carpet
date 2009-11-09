<?php
/*      
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db_vars.php';
//error_reporting(E_ALL);

if($_REQUEST["assign"] == "update") {

    $id = $_REQUEST['id'];
    if ($stmt = $conn->prepare("delete from groupuser where memberid = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
    
    if(!$_POST['groupid'] == 0) {
        $stmt = $conn->prepare("insert into groupuser(groupid,memberid) values (?,?)");
        if ($stmt) {
            $stmt->bind_param('dd',$_POST['groupid'],$id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Staff with id  ".$_POST['id']." was assigned to a group.";
            }

        }
    }
    else
    {
        echo "Staff with id  ".$_POST['id']." is NOT assigned to any group.";
    }

}

function remove_assignment($id) {
    
    if ($stmt = $conn->prepare("delete from groupuser where memberid = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}

if($_REQUEST["assign"] == "loadpanal1") {
    $id = $_REQUEST['id'];
    if($id != 0) {
        $stmt = $conn->prepare("select CONCAT(Fname, ' ', Lname) as name, m.MemberId as memeberid from member m, groupuser g where m.MemberId = g.memberid and groupid = ?");

        if ($stmt) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->bind_result($name,$mid);

            $str = "[";
            while ($stmt->fetch()) {
                $str .= "{optionValue: ".$mid.",optionDisplay: '".$name."'},";
            }
            $str .= "]";
            echo $str;

        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }

    }
    else {
        $stmt = $conn->prepare("select CONCAT(Fname, ' ', Lname) as name, m.MemberId as memeberid from member m");

        if ($stmt) {

            $stmt->execute();
            $stmt->bind_result($name,$mid);


            $str = "[";
            $i = 0;
            while ($stmt->fetch()) {
            // $str .= "{optionValue: ".$id.",optionDisplay: '".$name."'},";
                $memberarray[$i]['id'] = $mid;
                $memberarray[$i]['name'] = $name;

                $i++;

            }

            foreach($memberarray as $key=>$value) {
                $stmt2 = $conn->prepare("select count(*) as ct from groupuser where memberid = ?");
                if($stmt2) {

                    $stmt2->bind_param('i', $value['id']);
                    $stmt2->execute();
                    $stmt2->bind_result($ct);

                    while ($stmt2->fetch()) {
                        if($ct == 0) {
                            $str .= "{optionValue: ". $value['id'].",optionDisplay: '". $value['name']."'},";
                        }
                    }
                }
            }
            $str .= "]";
            echo $str;
        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }
    }

}



if($_REQUEST["assign"] == "get_data") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("SELECT GroupId,GroupName,Payment from paygroup");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($GroupId,$GroupName,$Payment);

        $str = "[";
        while ($stmt->fetch()) {
            $str .= "{optionValue: ".$GroupId.",optionDisplay: '".$GroupName."'},";
        }
        $str .= "]";
        echo $str;

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}

if($_REQUEST["stafffunction"] == "get_desig") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("SELECT id,code,name from designation");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);
        $str = "[";
        while ($stmt->fetch()) {
            $str .= "{optionValue: ".$id.",optionDisplay: '".$name."'},";
        }
        $str .= "]";
        echo $str;

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}

if($_REQUEST["stafffunction"] == "get_dept") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("SELECT id,code,name from department");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);
        $str = "[";
        while ($stmt->fetch()) {
            $str .= "{optionValue: ".$id.",optionDisplay: '".$name."'},";
        }
        $str .= "]";
        echo $str;

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}





if($_REQUEST['stafffunction'] == "addnew") {

    $stmt = $conn->prepare("INSERT INTO member(Fname,Lname,Idname,PositionId,DeptId,NRIC,ActiveStatus,RegistrationDate,LastUpdate)
                              VALUES (?,?,?,?,?,?,?,now(),now())");
    $stmt->bind_param('sssddss', $_POST['fname'],$_POST['lname'],$_POST['idname'],$_POST['position'],$_POST['department'],$_POST['nirc'],$_POST['status']);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Staff ".$_POST['fname']." ".$_POST['lname']." Added";
    }else {
        echo "Staff not added. please try again later.";
    }



}


if($_REQUEST['stafffunction'] == "listall") {

    $stmt = $conn->prepare("SELECT MemberId,Fname,Lname,Idname,PositionId,DeptId,NRIC,ActiveStatus,RegistrationDate from member");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($MemberId,$Fname,$Lname,$Idname,$PositionId,$DeptId,$NRIC,$ActiveStatus,$RegistrationDate);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>

        <th>First Name</th>
        <th>Last Name</th>
        <th>ID Name</th>
        <th>Designation</th>
        <th>Deparment</th>
        <th>NRIC</th>
        <th>Status</th>
        <th>Registered on</th>
        <th>Action</th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";

                echo "<td>".$Fname."</td>";
                echo "<td>".$Lname."</td>";
                echo "<td>".$Idname."</td>";
                echo "<td>".$PositionId."</td>";
                echo "<td>".$DeptId."</td>";
                echo "<td>".$NRIC."</td>";
                echo "<td>".$ActiveStatus."</td>";
                echo "<td>".substr($RegistrationDate,0,10)."</td>";
                echo "<td>";
                ?>
    <a href="#" id="delete" OnClick="deleteuser(<?php echo $MemberId;?>);"> Delete </a>|
    <a href="#" id="edit" OnClick="edituser(<?php echo $MemberId;?>);"> Edit </a>
                <?php
                echo "</td>";

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

if($_REQUEST['stafffunction'] == "delete") {

    $stmt = $conn->prepare("delete from member where MemberId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "User with id  ".$_POST['id']." was deleted.";
        }
    }
}



if($_REQUEST["stafffunction"] == "details") {
    $id = $_REQUEST['id'];


    $stmt = $conn->prepare("SELECT MemberId,Fname,Lname,Idname,PositionId,DeptId,NRIC,ActiveStatus,RegistrationDate from member where MemberId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($MemberId,$Fname,$Lname,$Idname,$PositionId,$DeptId,$NRIC,$ActiveStatus,$RegistrationDate);

        while ($stmt->fetch()) {
            echo "[ {id: '".$MemberId."',fname: '".$Fname."', lname: '".$Lname."', idname: '".$Idname."', position: '".$PositionId."', Deparment: '".$DeptId."', nric: '".$NRIC."', status: '".$ActiveStatus."', regdate: '".$RegistrationDate."'}]";

        }

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}


if($_REQUEST["stafffunction"] == "update") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("update member set Fname = ?, Lname = ?, Idname = ?, PositionId = ?, DeptId = ?, NRIC = ?, ActiveStatus = ?, LastUpdate = now() where MemberId = ?");
    if ($stmt) {
        $stmt->bind_param('sssddssd',$_POST['fname'],$_POST['lname'],$_POST['idname'],$_POST['position'],$_POST['department'],$_POST['nirc'],$_POST['status'], $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Staff with id  ".$_POST['id']." was updated.";
        }

    }
}



if($_REQUEST['deptfunction'] == "addnew") {

    $stmt = $conn->prepare("INSERT INTO department(code,name)
                              VALUES (?,?)");
    $stmt->bind_param('ss', $_POST['code'],$_POST['name']);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Department '".$_POST['name']."' Added";
    }else {
        echo "Department not added. please try again later.";
    }

}

if($_REQUEST['deptfunction'] == "listall") {

    $stmt = $conn->prepare("SELECT id,code,name from department");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>

        <th>Code</th>
        <th>Name</th>        
        <th>Action</th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";

                echo "<td>".$code."</td>";
                echo "<td>".$name."</td>";
                echo "<td>";
                ?>
    <a href="#" id="delete" OnClick="deleteuser(<?php echo $id;?>);"> Delete </a>|
    <a href="#" id="edit" OnClick="edituser(<?php echo $id;?>);"> Edit </a>
                <?php
                echo "</td>";

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



if($_REQUEST['deptfunction'] == "delete") {

    $stmt = $conn->prepare("delete from department where id = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Department with id  ".$_POST['id']." was deleted.";
        }
    }
}


if($_REQUEST["deptfunction"] == "details") {
    $id = $_REQUEST['id'];


    $stmt = $conn->prepare("SELECT id,code,name from department where id = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);

        while ($stmt->fetch()) {
            echo "[ {id: '".$id."',code: '".$code."', name: '".$name."'}]";

        }

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}


if($_REQUEST["deptfunction"] == "update") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("update department set code = ?, name = ? where id = ?");
    if ($stmt) {
        $stmt->bind_param('ssd',$_POST['code'],$_POST['name'],$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Department with id  ".$_POST['id']." was updated.";
        }

    }
}


if($_REQUEST['desigfunction'] == "addnew") {

    $stmt = $conn->prepare("INSERT INTO designation(code,name)
                              VALUES (?,?)");
    $stmt->bind_param('ss', $_POST['code'],$_POST['name']);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Designation '".$_POST['name']."' Added";
    }else {
        echo "Designation not added. please try again later.";
    }

}

if($_REQUEST['desigfunction'] == "listall") {

    $stmt = $conn->prepare("SELECT id,code,name from designation");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>

        <th>Code</th>
        <th>Designation Title</th>
        <th>Action</th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";

                echo "<td>".$code."</td>";
                echo "<td>".$name."</td>";
                echo "<td>";
                ?>
    <a href="#" id="delete" OnClick="deleteuser(<?php echo $id;?>);"> Delete </a>|
    <a href="#" id="edit" OnClick="edituser(<?php echo $id;?>);"> Edit </a>
                <?php
                echo "</td>";

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



if($_REQUEST['desigfunction'] == "delete") {

    $stmt = $conn->prepare("delete from designation where id = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Designation with id  ".$_POST['id']." was deleted.";
        }
    }
}


if($_REQUEST["desigfunction"] == "details") {
    $id = $_REQUEST['id'];


    $stmt = $conn->prepare("SELECT id,code,name from designation where id = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);

        while ($stmt->fetch()) {
            echo "[ {id: '".$id."',code: '".$code."', name: '".$name."'}]";

        }

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}


if($_REQUEST["desigfunction"] == "update") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("update designation set code = ?, name = ? where id = ?");
    if ($stmt) {
        $stmt->bind_param('ssd',$_POST['code'],$_POST['name'],$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Designation with id  ".$_POST['id']." was updated.";
        }

    }
}




if($_REQUEST['paymentgrp'] == "addnew") {

    $stmt = $conn->prepare("INSERT INTO paygroup(GroupName,Payment)
                              VALUES (?,?)");
    $stmt->bind_param('sd', $_POST['name'],$_POST['payment']);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "New payment group '".$_POST['name']."' Added";
    }else {
        echo "Payment Group not added. please try again later.";
    }

}

if($_REQUEST['paymentgrp'] == "listall") {

    $stmt = $conn->prepare("SELECT GroupId,GroupName,Payment from paygroup");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($GroupId,$GroupName,$Payment);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>

        <th>Id</th>
        <th>Group Name</th>
        <th>Payment</th>
        <th>Action</th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";

                echo "<td>".$GroupId."</td>";
                echo "<td>".$GroupName."</td>";
                echo "<td>".$Payment."</td>";
                echo "<td>";
                ?>
    <a href="#" id="delete" OnClick="deleteuser(<?php echo $GroupId;?>);"> Delete </a>|
    <a href="#" id="edit" OnClick="edituser(<?php echo $GroupId;?>);"> Edit </a>
                <?php
                echo "</td>";

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



if($_REQUEST['paymentgrp'] == "delete") {

    $stmt = $conn->prepare("delete from paygroup where GroupId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Payment group with id  ".$_POST['id']." was deleted.";
        }
    }
}


if($_REQUEST["paymentgrp"] == "details") {
    $id = $_REQUEST['id'];


    $stmt = $conn->prepare("SELECT GroupId,GroupName,Payment from paygroup where GroupId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($GroupId,$GroupName,$Payment);

        while ($stmt->fetch()) {
            echo "[ {id: '".$GroupId."',payment: '".$Payment."', name: '".$GroupName."'}]";

        }

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}


if($_REQUEST["paymentgrp"] == "update") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("update paygroup set GroupName = ?, Payment = ? where GroupId = ?");
    if ($stmt) {
        $stmt->bind_param('sdi',$_POST['name'],$_POST['payment'],$id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Payment Group with id  ".$_POST['id']." was updated.";
        }

    }
}
?>



