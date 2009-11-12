<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db_vars.php';
//error_reporting(E_ALL);

if($_REQUEST["reservation"] == "validate") {
//  print_r($_POST);
    $data = explode(",",$_POST['data']);
    //print_r($data);
    foreach($data as $key=>$value) {
            
    }
    

}

if($_REQUEST["reservation"] == "parkinglots") {
  $stmt = $conn->prepare("select title,PkLotId from parkinglot");

        if ($stmt) {

            $stmt->execute();
            $stmt->bind_result($title,$PkLotId);

            $str = "[";
            while ($stmt->fetch()) {
                $str .= "{optionValue: ".$PkLotId.",optionDisplay: '".$title."'},";
            }
            $str = substr($str,0,strlen($str)-1);
            $str .= "]";
            echo $str;

        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }

}



if($_REQUEST["filterby"] == "department") {
    $name = $_REQUEST['id'];

        $stmt = $conn->prepare("select CONCAT(Fname, ' ', Lname) as name, m.MemberId as memeberid from member m where DeptId = ?");

        if ($stmt) {
            
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt->bind_result($name,$mid);

            $str = "[";
            while ($stmt->fetch()) {
                $str .= "{optionValue: ".$mid.",optionDisplay: '".$name."'},";
            }
            $str = substr($str,0,strlen($str)-1);
            $str .= "]";
            echo $str;

        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }



}


if($_REQUEST["filter"] == "departments") {
    $stmt = $conn->prepare("SELECT id,code,name from department");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($id,$code,$name);
        $str = "[";
        while ($stmt->fetch()) {
            $str .= "{optionValue: '".$code."',optionDisplay: '".$name."'},";
        }
        $str = substr($str,0,strlen($str)-1);
        $str .= "]";
        echo $str;

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}


if($_REQUEST["filterby"] == "name") {
    $name = $_REQUEST['name'];
  
        $stmt = $conn->prepare("select CONCAT(Fname, ' ', Lname) as name, m.MemberId as memeberid from member m, groupuser g where m.MemberId = g.memberid and Fname like ?");

        if ($stmt) {
            $name = "%".$name."%";
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt->bind_result($name,$mid);

            $str = "[";
            while ($stmt->fetch()) {
                $str .= "{optionValue: ".$mid.",optionDisplay: '".$name."'},";
            }
            $str = substr($str,0,strlen($str)-1);
            $str .= "]";
            echo $str;

        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }

  

}


if($_REQUEST["filterby"] == "paygroup") {
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
            $str = substr($str,0,strlen($str)-1);
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
            $str = substr($str,0,strlen($str)-1);
            $str .= "]";
            echo $str;
        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }
    }

}


if($_REQUEST["filter"] == "paygroups") {
    $id = $_REQUEST['id'];

    $stmt = $conn->prepare("SELECT GroupId,GroupName,Payment from paygroup");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($GroupId,$GroupName,$Payment);

        $str = "[";
        while ($stmt->fetch()) {
            $str .= "{optionValue: ".$GroupId.",optionDisplay: '".$GroupName."'},";
        }
        $str = substr($str,0,strlen($str)-1);
        $str .= "]";
        echo $str;

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}

if($_REQUEST["parking"] == "get_data") {
  $stmt = $conn->prepare("select CONCAT(Fname, ' ', Lname) as name, m.MemberId as memeberid from member m");

        if ($stmt) {
           
            $stmt->execute();
            $stmt->bind_result($name,$mid);

            $str = "[";
            while ($stmt->fetch()) {
                $str .= "{optionValue: ".$mid.",optionDisplay: '".$name."'},";
            }
            $str = substr($str,0,strlen($str)-1);
            $str .= "]";
            echo $str;

        }
        else {
    /* Error */
            printf("Prepared Statement Error: %s\n", $mysqli->error);
        }

}

if($_REQUEST['parking'] == "addnew") {

    $stmt = $conn->prepare("INSERT INTO parkinglot(title,Location,NBays,remarks,AllowMoreCars,MemberPackage,DailyPackage,HourlyPackage) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssisssss', $_POST['title'],$_POST['location'], $_POST['nbays'], $_POST['remarks'], $_POST['morecars'], $_POST['memberpackage'], $_POST['dailypackage'], $_POST['hourlypackage']);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Parkig Lot ".$_POST['title']." Added";
    }else {
        echo "Parking Lot Not Added. Please try again later.";
    }



}



if($_REQUEST['parking'] == "listall") {

    $stmt = $conn->prepare("SELECT PkLotId,title,Location,NBays,remarks,AllowMoreCars,MemberPackage,DailyPackage,HourlyPackage from parkinglot");
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($PkLotId,$title,$Location,$NBays,$remarks,$AllowMoreCars,$MemberPackage,$DailyPackage,$HourlyPackage);
        ?>
<br /><br /><br />
<table id="userlisttable" border="1">
    <tr>

        <th>Title</th>
        <th>Location</th>
        <th>NBays</th>
        <th>Remarks</th>
        <th>AllowMoreCars</th>
        <th>MemberPackage</th>
        <th>DailyPackage/th>
        <th>HourlyPackage</th>
        <th>Action</th>
    </tr>


            <?php

            while ($stmt->fetch()) {
                echo "<tr>";

                echo "<td>".$title."</td>";
                echo "<td>".$Location."</td>";
                echo "<td>".$NBays."</td>";
                echo "<td>".$remarks."</td>";
                if($AllowMoreCars == "1")
                    echo "<td>Yes</td>";
                else
                    echo "<td>No</td>";
                if($MemberPackage == "1")
                    echo "<td>Yes</td>";
                else
                    echo "<td>No</td>";
                if($DailyPackage == "1")
                    echo "<td>Yes</td>";
                else
                    echo "<td>No</td>";
                if($HourlyPackage == "1")
                    echo "<td>Yes</td>";
                else
                    echo "<td>No</td>";

                echo "<td>";
                ?>
    <a href="#" id="delete" OnClick="deleteuser(<?php echo $PkLotId;?>);"> Delete </a>|
    <a href="#" id="edit" OnClick="edituser(<?php echo $PkLotId;?>);"> Edit </a>
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

if($_REQUEST['parking'] == "delete") {

    $stmt = $conn->prepare("delete from parkinglot where PkLotId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $_POST['id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Parking lot with id  ".$_POST['id']." was deleted.";
        }
    }
}


if($_REQUEST["parking"] == "details") {
    $id = $_REQUEST['id'];


    $stmt = $conn->prepare("SELECT PkLotId,title,Location,NBays,remarks,AllowMoreCars,MemberPackage,DailyPackage,HourlyPackage from parkinglot where PkLotId = ?");
    if ($stmt) {
        $stmt->bind_param('d', $id);
        $stmt->execute();
        $stmt->bind_result($PkLotId,$title,$Location,$NBays,$remarks,$AllowMoreCars,$MemberPackage,$DailyPackage,$HourlyPackage);

        while ($stmt->fetch()) {
            echo "[ {id: '".$PkLotId."',title: '".$title."',location: '".$Location."', nbays: '".$NBays."', remarks: '".$remarks."', morecars: '".$AllowMoreCars."', memberpackage: '".$MemberPackage."', dailypackage: '".$DailyPackage."', hourlypackage: '".$HourlyPackage."'}]";

        }

    }
    else {
    /* Error */
        printf("Prepared Statement Error: %s\n", $mysqli->error);
    }

}

if($_REQUEST["parking"] == "update") {
        $id = $_REQUEST['id'];
       
        $stmt = $conn->prepare("update parkinglot set title = ?, Location = ?,  NBays = ?, remarks = ?, AllowMoreCars = ?, MemberPackage = ?, DailyPackage = ?, HourlyPackage = ? where PkLotId = ?");
    if ($stmt) {
       $stmt->bind_param('ssisssssi', $_POST['title'],$_POST['location'], $_POST['nbays'], $_POST['remarks'], $_POST['morecars'], $_POST['memberpackage'], $_POST['dailypackage'], $_POST['hourlypackage'], $id);
        $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Parking Lot with id  ".$_POST['id']." was updated.";
    }

  }
}


?>
