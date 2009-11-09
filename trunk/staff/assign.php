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


                $.getJSON("process.php?",{assign: 'get_data'}, function(j){
                    var options = '';
                    options += '<option>Please Select One</option>';
                    options += '<option value="0">NOT IN A GROUP</option>';
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }
                    $("select#listone").html(options).selectmenu({
                        menuWidth: 200
                    });

                });


                $.getJSON("process.php?",{assign: 'get_data'}, function(j){
                    var options = '';
                    options += '<option>Please Select One</option>';
                    for (var i = 0; i < j.length; i++) {
                        options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                    }
                    options += '<option value="0">NOT IN A GROUP</option>';
                    $("select#listtwo").html(options).selectmenu({
                        menuWidth: 200
                    });

                });



                $("#listone").change(function() {
                    //console.log($(this).val());

                    $.getJSON("process.php?",{assign: 'loadpanal1', id: $(this).val()}, function(j){
                        var options = '';
                        for (var i = 0; i < j.length; i++) {
                            options += '<li id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</li>';
                        }
                        options += '';
                        $("#panel1").html(options);


                        $('li',"#panel1").draggable({
                            cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
                            revert: 'invalid', // when not dropped, the item will revert back to its initial position
                            containment: $('#container').length ? '#container' : 'document', // stick to demo-frame if present
                            helper: 'clone',
                            cursor: 'move'
                        });

                        $("#panel2").droppable({
                            accept: '#panel1 > li',
                            activeClass: 'panelhighlight',
                            drop: function(ev, ui) {
                                moveStaff(ui.draggable);

                            }
                        });

                        $("#panel1").droppable({
                            accept: '#panel2 > li',
                            activeClass: 'panelhighlight',
                            drop: function(ev, ui) {
                                moveStaffBack(ui.draggable);

                            }
                        });

                    });
                });



                $("#listtwo").change(function() {
                    //console.log($(this).val());

                    $.getJSON("process.php?",{assign: 'loadpanal1', id: $(this).val()}, function(j){
                        var options = '';
                        for (var i = 0; i < j.length; i++) {
                            options += '<li id="' + j[i].optionValue + '">' + j[i].optionDisplay + '</li>';
                        }
                        options += '';
                        $("#panel2").html(options);


                        $('li',"#panel2").draggable({
                            cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
                            revert: 'invalid', // when not dropped, the item will revert back to its initial position
                            containment: $('#container').length ? '#container' : 'document', // stick to demo-frame if present
                            helper: 'clone',
                            cursor: 'move'
                        });

                        $("#panel1").droppable({
                            accept: '#panel2 > li',
                            activeClass: 'panelhighlight',
                            drop: function(ev, ui) {
                                moveStaffBack(ui.draggable);

                            }
                        });

                        $("#panel2").droppable({
                            accept: '#panel1 > li',
                            activeClass: 'panelhighlight',
                            drop: function(ev, ui) {
                                moveStaff(ui.draggable);

                            }
                        });

                    });
                });
            });


            function moveStaff($item) {
                //   console.log($item.attr('id'));
                var itemExists = false;

                var $list = $("#panel2");
                $list.find('li').each(function(){
                    if($(this).attr('id') == $item.attr('id')) {
                        itemExists = true;
                    }
                });

                if(!itemExists) {
                    $item.fadeOut(function() {
                        $item.appendTo($list).slideDown(1000);

                        //update database
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                assign: "update",
                                id: $item.attr('id'),
                                groupid: $("#listtwo").val()
                            }),
                            success: function(msg)
                            {
                                $("#top").html(msg);
                            }
                        });


                    });
                }
            }

            function moveStaffBack($item) {
                var itemExists = false;

                var $list = $("#panel1");
                $list.find('li').each(function(){
                    if($(this).attr('id') == $item.attr('id')) {
                        itemExists = true;
                    }
                });

                if(!itemExists) {
                    $item.fadeOut(function() {
                        $item.appendTo($list).slideDown(1000);

                        //update database
                        $.ajax({
                            type: "POST",
                            url: "process.php",
                            data: ({
                                assign: "update",
                                id: $item.attr('id'),
                                groupid: $("#listone").val()
                            }),
                            success: function(msg)
                            {
                                $("#top").html(msg);
                            }
                        });

                    });
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
            <div id="leftnav" align="center">

                <strong>Select group for pane 1</strong><select id="listone"><option value="">Sample One</option></select>
                <strong>Select group for pane 2</strong><select id="listtwo"><option value="">Sample One</option></select>

            </div>
            <div id="content">

                <div id="panel1"></div><div id="panel2"></div>


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

