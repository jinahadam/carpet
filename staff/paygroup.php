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
                    if($("#addgrp").valid()) {
                        console.log("form valid");
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                paymentgrp: "update",
                                id: $("#edit_id").val(),
                                payment: $("#payment").val(),
                                name: $("#name").val(),

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
                        $("#listallresultdiv").load("process.php?paymentgrp=listall", function() {
                            $("#listallresultdiv").slideDown(1000);
                        });
                    });
                });

                $("#addnew").click(function() {
                    $(".addbuttondiv").show();
                    $(".editbuttondiv").hide();
                    clearnewstaffform();
                });



                $("#addgrp").validate({
                    errorElement: "span",
                    rules: {
                        payment: {
                            required: true,
                            max: 1000
                        }
                    }
                });

                $("#add").click(function() {
                    if($("#addgrp").valid())
                    {
                        console.log("form valid");

                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                paymentgrp: "addnew",
                                payment: $("#payment").val(),
                                name: $("#name").val()
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
            });


            function clearnewstaffform() {
                $("#content").hide();
                $("#listallresultdiv").slideUp(1000);
                $("#content").slideDown(1000);
                $("#payment").val("");
                $("#name").val("");

            }


            function deleteuser(id) {
                $.ajax({
                    type: "POST",
                    url: "process.php",
                    data: "paymentgrp=delete&id=" + id,
                    success: function(msg){
                        $("#top").html(msg);
                        $("#content").slideUp(1000, function() {
                            $('#listallresultdiv').slideDown(1000);
                            $("<img src='../images/loading.gif' id='ajaxloading' alt='loading list of staff' />").appendTo("#listallresultdiv");
                            $("#listallresultdiv").load("process.php?paymentgrp=listall", function() {
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


                $.getJSON("process.php?paymentgrp=details&id="+ id, function(j){
                    console.log(j[0]);
                    $("#edit_id").val(j[0].id);
                    $("#payment").val(j[0].payment);
                    $("#name").val(j[0].name);


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

                    <form id="addgrp" method="post" action="" >
                        <input type="hidden" id="edit_id" />
                        <table>
                            <tr>
                                <td><label for="name">Group Name:</label></td>
                                <td><input id="name" name="code" type="text" class="required"   /></td>
                            </tr>
                            <tr>
                                <td><label for="name">Payment</label></td>
                                <td><input id="payment" name="payment" type="text" class="required"  /></td>
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