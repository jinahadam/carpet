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
                $("#content").hide();

                $("#listall").click(function() {
                    $("#content").slideUp(1000, function() {
                        $("#content").load("process.php?entries=listall", function() {
                            $("#content").slideDown(1000);
                        });
                    });

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
                    <li><a href="#" id="search">Search</a></li>
                    <li><a href="#" id="listall">Last 30 Entries</a></li>
                    <li><a href="#" id="other">Data Logs</a></li>
                    

                </ul>
            </div>
            <div id="content">
                <h2>Last 30 entries to the Log</h2>
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