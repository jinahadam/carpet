<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../css/global.css" type="text/css" rel="stylesheet" /> <!-- global style sheet -->
        <link href="../css/ui.selectmenu.css" type="text/css" rel="stylesheet" />
        <link href="../css/jquery-ui.css" type="text/css" rel="stylesheet" />
        <link href="../css/ui.daterangepicker.css" type="text/css" rel="stylesheet" />
        <link href="../css/jquery-ui.css" type="text/css" rel="stylesheet" />
        <link href="../css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" rel="stylesheet" />
        <link href="../css/asmselect.css" type="text/css" rel="stylesheet" />


        <script type="text/javascript" src="../js/jquery.js"></script> <!-- include the jquery framework -->
        <script type="text/javascript" src="../js/jquery-ui.js"></script>
        <script type="text/javascript" src="../js/ui.selectmenu.js"></script>
        <script type="text/javascript" src="../js/validate.js"></script>
        <script type="text/javascript" src="../js/daterangepicker.js"></script>
        <script type="text/javascript" src="../js/asmselect.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $("#listallresultdiv").slideDown(1000);

                $("#days").hide();
                $("#days").asmSelect({
                    addItemTarget: 'top',
                    animate: true,
                    highlight: true,
                    sortable: true

                }).after($("<a href='#'>All days of the week</a>").click(function() {
                    $("#days").children().attr("selected", "selected").end().change();
                    return false;
                }));


                toggleSelectDays();

                var selectedStaff = [];
                $.getJSON("process.php?",{reservation: 'parkinglots'}, function(j){

                    var options = '';

                    for (var i = 0; i < j.length; i++) {

                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }

                    $("#parkinglots").html(options);
                });




                $("select#filterchoice").selectmenu();

                $("select#filterchoice").change(function() {
                    if($("#assigndiv").css("display") != "none") {
                        $("#assigndiv").hide("slide", { direction: "left" }, 1000);
                    }
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

                $('select#mstafflist,#days').addClass("SidleField");
                $('select#mstafflist,#days').focus(function() {
                    $(this).removeClass("SidleField").addClass("SfocusField");


                });
                $('select#mstafflist,#days').blur(function() {
                    $(this).removeClass("SfocusField").addClass("SidleField");
                    if ($.trim(this.value) == ''){
                        this.value = (this.defaultValue ? this.defaultValue : '');
                    }
                });

                $("#mstafflist").change(function() {

                    selectedStaff.splice(0,selectedStaff.length);

                    $('#mstafflist :selected').each(function(i, selected){
                        selectedStaff[i] = $(selected).val();
                    });

                    if(selectedStaff.length > 0) {
                        //load parking lots


                        if($("#assigndiv").css('display') == 'none') {




                            $("#assigndiv").show("slide", { direction: "left" }, 1000, function() {
                                $("#daterange").daterangepicker( {
                                    presetRanges: [
                                        {
                                            text: 'Tomorrow', dateStart: 'tomorrow'  , dateEnd: 'tomorrow'
                                        },
                                        {
                                            text: 'This Week', dateStart: 'Previous Monday'  , dateEnd: 'Next Sunday'
                                        },
                                        {
                                            text: 'Next Week', dateStart: 'Next Monday'  , dateEnd: 'next monday + 7'
                                        },
                                        {
                                            text: 'This Month', dateStart: function(){ return Date.parse('today').moveToFirstDayOfMonth();  }, dateEnd: function(){ return Date.parse('today').moveToLastDayOfMonth();  }
                                        },
                                        {
                                            text: 'Rest fo the Month', dateStart: 'today ', dateEnd: function(){ return Date.parse('today').moveToLastDayOfMonth();  }
                                        },
                                        {
                                            text: 'Next Month', dateStart: function(){ return Date.parse('next month').moveToFirstDayOfMonth();  }, dateEnd: function(){ return Date.parse('next month').moveToLastDayOfMonth();  }
                                        }


                                    ],
                                    onChange: function() {
                                        toggleSelectDays();
                                    }

                                } );
                            });
                            $("#parkinglots").selectmenu();



                        }
                    }
                    if(selectedStaff.length == 0) {
                        if($("#assigndiv").css("display") != "none") {
                            $("#assigndiv").hide("slide", { direction: "left" }, 1000);
                        }
                    }


                });


                $("#assigndiv").hide();


                $("#assign").click(function() {



                    $.ajax({
                        type: "POST",
                        url: "process.php",
                        data: ({
                            reservation: "validate",
                            data: selectedStaff.toString()
                        }),
                        success: function(msg)
                        {   
                        }
                    });
                });



            });

            function toggleSelectDays() {

                if($("#daterange").val().length > 10) {



                    if($("#dayselection").css("display") == "none") {
                        $("#dayselection").slideDown(1000);
                    }

                }
                else {
                    if($("#dayselection").css("display") != "none") {
                        $("#dayselection").slideUp(1000);
                    }
                }
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
            <div id="leftnav" >
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

                </div>
                <div id="filteroptionsdiv"></div>



                <div id="content_2"><br /><br /><br /><br />
                    <div id="assigndiv">
                        <h2>Parking Lot:</h2>
                        <select id="parkinglots">
                            <option>1</option>
                        </select><br />
                        <input type="text" id="daterange" />
                        <div id="dayselection"><br />
                            <select id="days" title="Select day to add" multiple="multiple">
                                <option value="1" selected>Monday</option>
                                <option value="2" selected>Tuesday</option>
                                <option value="3" selected>Wednesday</option>
                                <option value="4" selected>Thursday</option>
                                <option value="5" selected>Friday</option>
                                <option value="6">Saturday</option>
                                <option value="7">Sunday</option>
                            </select>
                        </div>
                        <br />
                        <br />

                        <input type="button"  id="assign" value="Assign" class="bigbutton"/>
                        <br /><br />
                    </div>


                    <div id="stafflist" >

                        <h2>Staff List</h2><select id="mstafflist" multiple="multiple" size="20"><option value="">Sample One</option></select>
                    </div>
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