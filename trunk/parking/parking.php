<html>
    <head>
        <title></title>
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

                $("#edit").click(function() {
                    if($("#addlot").valid()) {
                        console.log("form valid");
                        
                        var morecars_val = "0";
                        if($('#morecars').attr('checked'))
                        {
                            morecars_val = "1";
                        }
                        var memberpackage_val = "0";
                        if($('#memberpackage').attr('checked'))
                        {
                            memberpackage_val = "1";
                        }
                        var dailypackage_val = "0";
                        if($('#dailypackage').attr('checked'))
                        {
                            dailypackage_val = "1";
                        }
                        var hourlypackage_val = "0";
                        if($('#hourlypackage').attr('checked'))
                        {
                            hourlypackage_val = "1";
                        }
                        
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                parking: "update",
                                id: $("#edit_id").val(),
                                title: $("#title").val(),
                                location: $("#location").val(),
                                nbays: $("#nbays").val(),
                                remarks: $("#remarks").val(),
                                morecars: morecars_val,
                                memberpackage: memberpackage_val,
                                dailypackage: dailypackage_val,
                                hourlypackage: hourlypackage_val

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
                        $("#listallresultdiv").load("process.php?parking=listall", function() {
                            $("#listallresultdiv").slideDown(1000);
                        });
                    });
                });

                $("#addnew").click(function() {
                    $(".addbuttondiv").show();
                    $(".editbuttondiv").hide();
                    clearnewstaffform();
                });



                $("#addlot").validate({
                    errorElement: "span",
                    rules: {
                        nbays: {
                            required: true,
                            digits: true
                        }
                    }
                });

                $("#add").click(function() {
                    if($("#addlot").valid())
                    {
                        console.log("form valid");

                        var morecars_val = "0";
                        if($('#morecars').attr('checked'))
                        {
                            morecars_val = "1";
                        }
                        var memberpackage_val = "0";
                        if($('#memberpackage').attr('checked'))
                        {
                            memberpackage_val = "1";
                        }
                        var dailypackage_val = "0";
                        if($('#dailypackage').attr('checked'))
                        {
                            dailypackage_val = "1";
                        }
                        var hourlypackage_val = "0";
                        if($('#hourlypackage').attr('checked'))
                        {
                            hourlypackage_val = "1";
                        }

                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                parking: "addnew",
                                title: $("#title").val(),
                                location: $("#location").val(),
                                nbays: $("#nbays").val(),
                                remarks: $("#remarks").val(),
                                morecars: morecars_val,
                                memberpackage: memberpackage_val,
                                dailypackage: dailypackage_val,
                                hourlypackage: hourlypackage_val
                            }),
                            success: function(msg)
                            {
                                $("#top").html(msg);
                                clearnewstaffform();
                            }
                        });
                    }
                    return false;
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



                $('textarea').addClass("TAidleField");
                $('textarea').focus(function() {
                    $(this).removeClass("TAidleField").addClass("TAfocusField");
                    if (this.value == this.defaultValue){
                        this.value = '';
                    }
                    if(this.value != this.defaultValue){
                        this.select();
                    }
                });
                $('textarea').blur(function() {
                    $(this).removeClass("TAfocusField").addClass("TAidleField");
                    if ($.trim(this.value) == ''){
                        this.value = (this.defaultValue ? this.defaultValue : '');
                    }
                });
            });


            function clearnewstaffform() {
                $("#content").hide();
                $("#listallresultdiv").slideUp(1000);
                $("#content").slideDown(1000);
                $("#title").val("");
                $("#location").val("");
                $("#nbays").val("");
                $("#remarks").val("");
                $("#morecars").val("");
                $("#memeberpackage").val("");
                $("#dailypackage").val("");
                $("#hourlypackage").val("");

            }


            function deleteuser(id) {
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: "parking=delete&id=" + id,
                    success: function(msg){
                        $("#top").html(msg);
                        $("#content").slideUp(1000, function() {
                            $('#listallresultdiv').slideDown(1000);
                            $("<img src='../images/loading.gif' id='ajaxloading' alt='loading list..' />").appendTo("#listallresultdiv");
                            $("#listallresultdiv").load("process.php?parking=listall", function() {
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


                $.getJSON("process.php?parking=details&id="+ id, function(j){
                    console.log(j[0]);
                    $("#edit_id").val(j[0].id);
                    $("#title").val(j[0].title);
                    $("#location").val(j[0].location);
                    $("#nbays").val(j[0].nbays);
                    $("#remarks").val(j[0].remarks);
                    if(j[0].morecars == "1") {
                        $('#morecars').attr('checked','checked');
                    } else {
                        $('#morecars').removeAttr("checked");
                    }
                    if(j[0].memberpackage == "1") {
                        $('#memberpackage').attr('checked','checked');
                    } else {
                        $('#memberpackage').removeAttr("checked");
                    }
                    if(j[0].dailypackage == "1") {
                        $('#dailypackage').attr('checked','checked');
                    } else {
                        $('#dailypackage').removeAttr("checked");
                    }
                    if(j[0].hourlypackage == "1") {
                        $('#hourlypackage').attr('checked','checked');
                    } else {
                        $('#hourlypackage').removeAttr("checked");
                    }



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
                    <li><a href="#" id="addnew">Add </a></li>
                    <li><a href="#" id="listall">List all </a></li>

                </ul>
            </div>
            <div id="content">
                <div id="addformdiv">

                    <form id="addlot" method="post" action="" >
                        <input type="hidden" id="edit_id" />
                        <table>
                            <tr>
                                <td><label for="title">Title:</label></td>
                                <td><input id="title" name="title" type="text" class="required"   /></td>
                            </tr>
                            <tr>
                                <td><label for="location">Location:</label></td>
                                <td><input id="location" name="location" type="text" class="required"   /></td>
                            </tr>
                            <tr>
                                <td><label for="nbays">No.of Bays:</label></td>
                                <td><input id="nbays" name="nbays" type="text" class="required"   /></td>
                            </tr>
                            <tr>
                                <td valign="top"><label for="remakrs">Remarks</label></td>
                                <td><textarea name="remarks" id="remarks" rows="4" cols="20"></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="morecars">Allow More Cars:</label></td>
                                <td><input type="checkbox"  id="morecars" name="morecars" /></td>
                            </tr>
                            <tr>
                                <td><label for="memberpackage">Member Package:</label></td>
                                <td><input type="checkbox"  id="memberpackage" name="memberpackage"/></td>
                            </tr>
                            <tr>
                                <td><label for="dailypackage">Daily Package:</label></td>
                                <td><input type="checkbox"  id="dailypackage" name="dailypackage" /></td>
                            </tr>
                            <tr>
                                <td><label for="hourlypackage">Hourly Package:</label></td>
                                <td><input type="checkbox"  id="hourlypackage" name="hourlypackage" /></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <div class="addbuttondiv"><input id="add" name="add" type="button" value="Add" class="bigbutton" /></div>
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