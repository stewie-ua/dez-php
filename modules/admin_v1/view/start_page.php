<?php
    use Sy\Core\Url;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DezAdmin [ <?=$title?> ]</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>    <link rel="stylesheet" href="<?=$base_url?>media/css/main.css"/>
    <script src="<?=$base_url?>media/js/jquery-2.1.1.min.js"></script>
    <script src="<?=$base_url?>media/js/dom.js"></script>
    <base href="<?=Url::base()?>"/>
</head>
<body>

<header>

    <div class="logo">dez-admin</div>

    <ul class="menu">
        <li><a href="<?=url( 'admin/auth' )?>">Авторизация</a></li>
        <li><a href="<?=url( 'admin/about_system' )?>">О системе</a></li>
    </ul>

</header>

<div class="box">

    <div class="left-block">
        <?=( isset( $left ) ? $left : null )?>
    </div>

    <div class="content fixed-height">
        <?=$error_block?>
        <?=$message_block?>
        <?=( isset( $content ) ? $content : null )?>
    </div>
    <div class="clr"></div>

</div>

<footer>

</footer>

</body>
</html>
