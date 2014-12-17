<html>
<head>
    <title>Database error [SyFramework]</title>
</head>
<body>
<style>
    *{
        font-family: arial;
    }
    body{
        background-color: #E7EFF0;
    }
    .sy-error-box{
        width: 80%;
        margin: 10% auto;
        background: rgb(255, 255, 255);
    }
    .sy-error-head{
        color: #9BA7AA;
        font-size: 40px;
        padding: 20px;
        text-shadow: 1px 1px 0px #FFFFFF;
        background-color: #E8EEF0;
    }
    .sy-error-body{
        border: 6px solid rgb(207, 213, 212);
        padding: 20px;
        color: #5A5F61;
        text-shadow: 1px 1px 0px #FFFFFF;
        font-size: 17px;
    }
</style>
<div class="sy-error-box">
    <div class="sy-error-head">
        Database error
    </div>
    <div class="sy-error-body">
        <b>
	        Message
        </b>
	    <br />
	    <?php print $error; ?>
    </div>
</div>
</body>
</html>