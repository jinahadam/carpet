<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
require_once '../config/db_vars.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../css/global.css" type="text/css" rel="stylesheet" /> <!-- global style sheet -->
        <link href="../css/validationEngine.jquery.css" type="text/css" rel="stylesheet" /> <!-- style sheet for arranging and styling form elements -->
        <link href="../js/jq/jqtransform.css" type="text/css" rel="stylesheet" /> <!-- style sheet for arranging and styling form elements -->
        <script type="text/javascript" src="../js/jquery.js"></script> <!-- include the jquery framework -->
        <script type="text/javascript" src="../js/jquery.validationEngine.js"></script> <!-- js for cleint side validation -->
        <script type="text/javascript" src="../js/jq/jquery.jqtransform.js"></script> <!-- js for arraning form element -->
        <script type="text/javascript">
            // page specific js
            function deleteuser(id) {
                 $.ajax({
                         type: "POST",
                         url: "usermanager.php",
                         data: "function=delete&id=" + id,
                         success: function(msg){
                                     $("#top").html(msg);
                                     $("#content").slideUp(1000, function() {
                                     $('#listallresultdiv').slideDown(1000);
                                     $("<img src='../images/loading.gif' id='ajaxloading' alt='loading list of users' />").appendTo("#listallresultdiv");
                                     $("#listallresultdiv").load("usermanager.php?function=listall", function() {
                                            $("#ajaxloading").remove();
                                     });
                                });
                                  
                                  }
                    });
            }


             function edituser(id) {
               
                 $('#listallresultdiv').slideUp(1000, function() {
                                
                                $("#edit_content").hide().slideDown(1000, function() {
                                        $.getJSON("usermanager.php?function=userdetails&id="+ id, function(j){
                                         console.log(j[0].email,j[0].username);
                                         $("#edit_username").val(j[0].username);
                                         $("#edit_email").val(j[0].email);
                                         $("#edit_id").val(id);
                                         
                                         

                                });

                        });

                    });

             }

            $(function() {
                $("#edituser").jqTransform();
                $("#edit_content").hide();
                $("#username").val("");
                
                $("#addnew").click(function() {
                        
                        $('#listallresultdiv').slideUp(1000, function() {
                                $("#content").hide().slideDown(1000).jqTransform();
                        });
                        

                });

                $("#listall").click(function() {
                        var d = ($("#edit_content").css('display'));
                        if( d != "none") {
                           $("#edit_content").slideUp(1000);
                        }
                        $("#edit_content").slideUp(1000);
                        $("#content").slideUp(1000, function() {
                             $('#listallresultdiv').slideDown(1000,function() {
                                    $("<img src='../images/loading.gif' id='ajaxloading' alt='loading list of users' />").appendTo("#listallresultdiv");
                                    $("#listallresultdiv").load("usermanager.php?function=listall", function() {
                                    $("#ajaxloading").remove();
                             });
                             });
                             
                        });
          
                });

                $("#addformdiv").hide().slideDown(1000).jqTransform()
                $("#adduser").validationEngine({
                    ajaxSubmit: true,
                    ajaxSubmitFile: "usermanager.php",
                    ajaxSubmitMessage: "User added.",
                    success :  function(){
                       $("#addnew").attr("href","adduser.php");
                    },
                    failure : function() {}

                });
             
                $("#edituser").validationEngine({
                     success: function() {
                                var edit_id = $("#edit_id").val();
                                var edit_email = $("#edit_email").val();
                                var edit_usertype = $("#edit_usertype").val();


                                $.ajax({
                                   type: "POST",
                                   url: "usermanager.php",
                                   data: "function=update&id=" + edit_id + "&email=" + edit_email + "&usertype=" + edit_usertype,
                                   success: function(msg){
                                            $("#top").html(msg).hide();
                                           
                                            $("#edit_content").slideUp(1000, function() {
                                                     $("#top").slideDown(1000, function() {
                                                         $("#edituser").submit();
                                                     });
                                                     
                                                
                                            });
                                          
                                   }
                                 });
                         

                }
                    
                });




                
                
                
               
               
               



            });


        </script>
        <title>Carpet</title>
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
                <li><a href="#" id="addnew">Add new user</a></li>
                <li><a href="#" id="listall">List all users</a></li>
                
                </ul>
            </div>
            <div id="content">
                <div id="addformdiv">
                    <br />
                    <form id="adduser" method="post" action="" >
                        <table>
                            <tr>
                                <td><label for="username">Username:</label></td>
                                <td><input id="username" name="username" type="text" class="validate[required,custom[noSpecialCaracters],length[5,20],ajax[ajaxUser]]"  /></td>

                                <td><label class="error"></label></td>
                            </tr>
                            <tr>
                                <td><label for="password">Password:</label></td>
                                <td><input id="password" name="password" type="password" value="welcome" class="validate[required,length[5,30]]" /><span class="note">(Default:welcome)</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="email">Email:</label></td>
                                <td><input id="email" name="email" type="text" class="validate[required,custom[email]] text-input" /></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="usertype">Usertype:</label></td>
                                <td>
                                    <select id="usertype" name="usertype" class="required">
                                        <option value="0">Administrator</option>
                                        <option value="1">Carpet Staff</option>
                                        <option value="2">Normal Staff</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input id="add" name="add" type="submit" value="Add User" /></td>
                            </tr>
                        </table>
                    </form>

                </div>

            </div>
            <div id="listallresultdiv"></div>

            <div id="edit_content">
                <div id="editformdiv">
                    <br />
                    <form id="edituser" method="post" action="" >
                        <table>
                            <tr>
                                <td><label for="edit_username">Username:</label></td>
                                <td><input id="edit_id"  name="edit_id" type="hidden" /><input id="edit_username" readonly="true" name="edit_username" type="text"  /></td>

                                <td><label class="error"></label></td>
                            </tr>
                           
                            <tr>
                                <td><label for="edit_email">Email:</label></td>
                                <td><input id="edit_email" name="edit_email" type="text" class="validate[required,custom[email]] text-input" /></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label for="edit_usertype">Usertype:</label></td>
                                <td>
                                    <select id="edit_usertype" name="usertype" class="required">
                                        <option value="0">Administrator</option>
                                        <option value="1">Carpet Staff</option>
                                        <option value="2">Normal Staff</option>
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input id="edit" name="edit" type="submit" value="Update" /><span id="updatemessage"></span></td>
                            </tr>
                        </table>
                    </form>

                </div>

            </div>

            
            <div id="footer">

            </div>
        </div>
        <div id="contentblock2">
            <div class="containingbox">

            </div>

        </div>
    </body>
</html>
