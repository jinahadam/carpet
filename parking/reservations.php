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


                $("select#filterchoice").selectmenu();

                $("select#filterchoice").change(function() {

                    var choice = $("#filterchoice").val();
                    if(choice == 0) {

                        $("#filteroptionsdiv").html("");

                        $.getJSON("process.php?",{parking: 'get_data'}, function(j){
                            var options = '';

                            for (var i = 0; i < j.length; i++) {
                                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                            }
                            $("#mstafflist").fadeOut("fast");
                            $("select#mstafflist").html(options);
                            $("#mstafflist").fadeIn("slowt");

                        });

                    } else if(choice == 1) {

                        $("#filteroptionsdiv").html("<h2>Payment Group</h2><select id='fpaygroups'><option> 1 </option></select>");
                        $("#s").selectmenu();

                        $.getJSON("process.php?",{filter: 'paygroups'}, function(j){
                            var options = '';
                            options += '<option>Select one</option>';
                            options += '<option id="0">UNASSIGNED</option>';
                            for (var i = 0; i < j.length; i++) {
                                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                            }

                            $("select#fpaygroups").html(options).selectmenu({
                                menuWidth: 200
                            });
                        });

                        $("#fpaygroups").change(function() {
                            console.log("changed");
                            $.getJSON("process.php?",{filterby: 'paygroup', id: $(this).val()}, function(j){
                                var options = '';
                                for (var i = 0; i < j.length; i++) {
                                    options += '<option id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                                }
                                options += '';
                                $("#mstafflist").fadeOut("fast");
                                $("#mstafflist").html(options);
                                $("#mstafflist").fadeIn("slow");
                            });

                        });

                    } else if(choice == 2) {
                        $("#filteroptionsdiv").html("<h2>Enter Name:</h2><input type='text' id='searchtext' />");

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

                        $("#searchtext").keyup(function() {
                            if($(this).val().length > 2) {
                                $.getJSON("process.php?",{filterby: 'name', name: $(this).val()}, function(j){
                                    var options = '';
                                    for (var i = 0; i < j.length; i++) {
                                        options += '<option id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                                    }
                                    options += '';
                                    $("#mstafflist").fadeOut("fast");
                                    $("#mstafflist").html(options);
                                    $("#mstafflist").fadeIn("slow");
                                });
                            } else if($(this).val().length == 0) {
                                $.getJSON("process.php?",{parking: 'get_data'}, function(j){
                                    var options = '';

                                    for (var i = 0; i < j.length; i++) {
                                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                                    }
                                    $("select#mstafflist").html(options);

                                });
                            }

                        });


                    }else if(choice == 3) {

                        $("#filteroptionsdiv").html("<h2>Select Department</h2><select id='departments'><option> 1 </option></select>");
                        $("#s").selectmenu();

                        $.getJSON("process.php?",{filter: 'departments'}, function(j){
                            var options = '';
                            options += '<option>Select one</option>';
                            for (var i = 0; i < j.length; i++) {
                                options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                            }

                            $("select#departments").html(options).selectmenu({
                                menuWidth: 200
                            });
                        });

                        $("#fpaygroups").change(function() {
                            console.log("changed");
                            $.getJSON("process.php?",{filterby: 'paygroup', id: $(this).val()}, function(j){
                                var options = '';
                                for (var i = 0; i < j.length; i++) {
                                    options += '<option id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                                }
                                options += '';
                                $("#mstafflist").fadeOut("fast");
                                $("#mstafflist").html(options);
                                $("#mstafflist").fadeIn("slow");
                            });

                        });


                        $("#departments").change(function() {
                            console.log("changed");
                            $.getJSON("process.php?",{filterby: 'department', id: $(this).val()}, function(j){
                                var options = '';
                                for (var i = 0; i < j.length; i++) {
                                    options += '<option id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                                }
                                options += '';
                                $("#mstafflist").fadeOut("fast");
                                $("#mstafflist").html(options);
                                $("#mstafflist").fadeIn("slow");
                            });

                        });
                    }


                });




                $.getJSON("process.php?",{parking: 'get_data'}, function(j){
                    var options = '';

                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }
                    $("select#mstafflist").html(options);

                });

                $('select#mstafflist').addClass("SidleField");
                $('select#mstafflist').focus(function() {
                    $(this).removeClass("SidleField").addClass("SfocusField");

                });
                $('select#mstafflist').blur(function() {
                    $(this).removeClass("SfocusField").addClass("SidleField");
                    if ($.trim(this.value) == ''){
                        this.value = (this.defaultValue ? this.defaultValue : '');
                    }
                });

            });



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
                <div id="filterdiv">
                    <h2>Filter Options</h2><select id="filterchoice" >
                        <option value="0">ALL</option>
                        <option value="1">Paygroups</option>
                        <option value="2">Name</option>
                        <option value="3">Department</option>
                    </select>
                    <div id="filteroptionsdiv"></div>
                </div>
                <div id="stafflist">

                    <h2>Staff List</h2><select id="mstafflist" multiple="multiple" size="20"><option value="">Sample One</option></select>
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