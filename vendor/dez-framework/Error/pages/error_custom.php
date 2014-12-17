<html>
<head>
    <title>Fatal error [SyFramework]</title>
</head>
<body>
<style>
    *{
        font-family: arial;
    }
    .sy-error-box{
        width: 80%;
        margin: 10% auto;
        background: rgb(240, 240, 240);
    }
    .sy-error-head{
        color: #5F7980;
        font-size: 40px;
        text-shadow: 1px 1px 0px #FFFFFF;
        font-weight: bold;
        /*border-bottom: 5px solid rgb(226, 226, 226);*/
        margin-bottom: 20px;
    }
    .sy-error-body{
        border: 20px solid rgb(116, 148, 143);
        padding: 20px;
        color: #353636;
        text-shadow: 1px 1px 0px #FFFFFF;
        font-size: 12px;
        font-family: "courier new";
        overflow-x: auto;
    }
    .sy-error-body pre {
        background: #fff;
        padding: 10px;
        border: 1px solid #000;
        white-space: pre-line;
    }
    .sy-error-body pre,
    .sy-error-body pre * {
        font-family: courier new;
        font-size: 12px;
    }
    .sy-backtrace, .sy-backtrace *{
	    font-family: courier new !important;
    }
</style>
<div class="sy-error-box">
    <div class="sy-error-body">
        <div class="sy-error-head">
            Fatal error
        </div>
        <pre>
            <?php print $error; ?>
        </pre>
    </div>
</div>
</body>
</html>