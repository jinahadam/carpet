<?php
require '../config/db_vars.php';




/* RECEIVE VALUE */
$validateValue=$_POST['validateValue'];
$validateId=$_POST['validateId'];
$validateError=$_POST['validateError'];

	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = $validateId;
	$arrayToJs[1] = $validateError;


//connect to the database and check weather the username exist

/* prepare statement */
if ($stmt = $conn->prepare("SELECT count(*) as count from useraccount where UserName = ?")) {

    $stmt->bind_param("s", $validateValue);
    $stmt->execute();

    /* bind variables to prepared statement */
    $stmt->bind_result($col1);

    /* fetch values */
    while ($stmt->fetch()) {
       $count =  $col1;
    }

    /* close statement */
    $stmt->close();
} 




if($count == 0){		// validate??
	$arrayToJs[2] = "true";			// RETURN TRUE
	echo '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';			// RETURN ARRAY WITH success
}else{
	for($x=0;$x<1000000;$x++){
		if($x == 990000){
			$arrayToJs[2] = "false";
			echo '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';		// RETURN ARRAY WITH ERROR
		}
	}

}

?>