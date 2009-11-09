
<?php
require_once '../config/db_vars.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../css/global.css" type="text/css" rel="stylesheet" /> <!-- global style sheet -->
        <link href="../css/ui.selectmenu.css" type="text/css" rel="stylesheet" />
        <link href="../css/jquery-ui.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="../js/jquery.js"></script> <!-- include the jquery framework -->
        <script type="text/javascript" src="../js/jquery-ui.js"></script>
        <script type="text/javascript" src="../js/ui.selectmenu.js"></script>
        <script type="text/javascript" src="../js/validate.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                          
                $.getJSON("process.php?",{stafffunction: 'get_desig'}, function(j){
                    var options = '';
                    console.log(j.length);
                    for (var i = 0; i < j.length; i++) {

                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }
                    $("select#position").html(options).selectmenu({
                            style:'dropdown',
                            menuWidth: 230
                        });
                });


                $.getJSON("process.php?",{stafffunction: 'get_dept'}, function(j){
                    var options = '';
                    console.log(j.length);
                    for (var i = 0; i < j.length; i++) {

                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }
                    $("select#department").html(options).selectmenu({
                            style:'dropdown',
                            menuWidth: 230
                        });
                });


                
                $("#status").selectmenu();
                

                $("#addstaff").validate({
                    errorElement: "span"
                });

                $("#addnew").click(function() {
                    $(".addbuttondiv").show();
                    $(".editbuttondiv").hide();
                    clearnewstaffform();
                });


                $("#add").click(function() {
                    if($("#addstaff").valid()) {
                        console.log("form valid");
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                stafffunction: "addnew",
                                fname: $("#fname").val(),
                                lname: $("#lname").val(),
                                idname: $("#idname").val(),
                                nirc: $("#nirc").val(),
                                position: $("#position").val(),
                                department: $("#deparment").val(),
                                status: $("#status").val()
                            }),

                            success: function(msg){
                                $("#top").html(msg);
                                clearnewstaffform();
                            }
                        });
                    }
                    return false;
                });



                $("#edit").click(function() {
                    if($("#addstaff").valid()) {
                        console.log("form valid");
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                stafffunction: "update",
                                id: $("#edit_id").val(),
                                fname: $("#fname").val(),
                                lname: $("#lname").val(),
                                idname: $("#idname").val(),
                                nirc: $("#nirc").val(),
                                position: $("#position").val(),
                                department: $("#department").val(),
                                status: $("#status").val()
                            }),

                            success: function(msg){
                                $("#top").html(msg);
                                $(".addbuttondiv").show();
                                $(".editbuttondiv").hide();
                                clearnewstaffform();
                            }
                        });
                    }
                    return false;
                });



                $("#listall").click(function() {
                    $("#content").slideUp(1000, function() {
                        $("#listallresultdiv").load("process.php?stafffunction=listall", function() {
                            $("#listallresultdiv").slideDown(1000);
                        });
                    });
                });



                $('input[type="text"]').addClass("idleField");
                $('input[type="text"]').focus(function() {
                    $(this).removeClass("idleField").addClass("focusField");
                    if (this.value == this.defaultValue){
                        this.value = '';
                    }
                    if(this.value != this.defaultValue){
                        this.select();
                    }
                });
                $('input[type="text"]').blur(function() {
                    $(this).removeClass("focusField").addClass("idleField");
                    if ($.trim(this.value) == ''){
                        this.value = (this.defaultValue ? this.defaultValue : '');
                    }
                });
            });

            function clearnewstaffform() {
                $("#content").hide();
                $("#listallresultdiv").slideUp(1000);
                $("#content").slideDown(1000);
                $("#fname").val("");
                $("#lname").val("");
                $("#idname").val("");
                $("#nirc").val("");
                $("#position").val("");
                $("#deparment").val("");
                $("#status").val("");
            }


            function deleteuser(id) {
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: "stafffunction=delete&id=" + id,
                    success: function(msg){
                        $("#top").html(msg);
                        $("#content").slideUp(1000, function() {
                            $('#listallresultdiv').slideDown(1000);
                            $("<img src='../images/loading.gif' id='ajaxloading' alt='loading list of staff' />").appendTo("#listallresultdiv");
                            $("#listallresultdiv").load("process.php?stafffunction=listall", function() {
                                $("#ajaxloading").remove();
                            });
                        });

                    }
                });
            }

            function edituser(id) {
                $(".addbuttondiv").hide();
                $(".editbuttondiv").show();
                clearnewstaffform();


                $.getJSON("process.php?stafffunction=details&id="+ id, function(j){
                    console.log(j[0]);
                    $("#edit_id").val(j[0].id);
                    $("#fname").val(j[0].fname);
                    $("#lname").val(j[0].lname);
                    $("#idname").val(j[0].idname);
                    $("#nirc").val(j[0].nric);
                    $("#status").val(j[0].status);
                    $("#regdate").val(j[0].regdate);

                });



            }

        </script>
    </head>
    <body>
        <div id="header">

        </div>
        <div id="contentblock">

        </div>
        <div id="container">
            <div id="top">

            </div>
            <div id="leftnav">
                <ul>
                    <li><a href="#" id="addnew">Add new staff</a></li>
                    <li><a href="#" id="listall">List all staff</a></li>

                </ul>
            </div>
            <div id="content">
                <div id="addstaffdiv">

                    <form id="addstaff" method="post" action="" >
                        <input type="hidden" id="edit_id" />
                        <table>
                            <tr>
                                <td><label for="fname">First Name</label></td>
                                <td><input id="fname" name="fname" type="text" class="required"  /></td>
                            </tr>
                            <tr>
                                <td><label for="lname">Last Name</label></td>
                                <td><input id="lname" name="lname" type="text" class="required"  /></td>
                            </tr>
                            <tr>
                                <td><label for="idname">Id Name</label></td>
                                <td><input id="idname" name="idname" type="text" class="required"  /></td>
                            </tr>


                            <tr>
                                <td><label for="position">Designation:</label></td>
                                <td>
                                    <select id="position" name="position" class="required">
                                              <option value="0">Student Service</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td><label for="department">Department:</label></td>
                                <td>
                                    <select id="department" name="department" class="required">
                                      
                                        <option value="1">Academic</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td><label for="nirc">NIRC</label></td>
                                <td><input id="nirc" name="nirc" type="text" class="required"  /></td>
                            </tr>

                            <tr>
                                <td><label for="status">Active Status:</label></td>
                                <td>
                                    <select id="status" name="status" class="required">
                                        <option value="0">Active</option>
                                        <option value="1">Not Active</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td><div class="editbuttondiv"><label for="regdate">Registered Date:</label></div></td>
                                <td><div class="editbuttondiv"><input id="regdate" name="regdate" type="text" readonly="true" /></div></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <div class="addbuttondiv"><input id="add" name="add" type="button" value="Add Staff" class="bigbutton" /></div>
                                    <div class="editbuttondiv"><input id="edit" name="edit" type="button" value="Update" class="bigbutton" /></div>

                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

            </div>
            <div id="listallresultdiv"></div>
            <div id="footer">

            </div>
        </div>
        <div id="contentblock2">
            <div class="containingbox">

            </div>

        </div>
    </body>

</html>