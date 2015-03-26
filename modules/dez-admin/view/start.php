<?php
use Dez\Core\Url, Dez\Web;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= $layout->getTitle(); ?></title>
        <?= $layout->getKeyword(); ?>
        <?= $layout->getDescription(); ?>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
        <?= $layout->get( 'head' ); ?>
    </head>
    <body id="web-page">



    <header>

        <div class="logo">DezAdmin</div>

        <ul class="menu">
            <li><a href="<?=adminUrl( 'index:home' )?>">Главная</a></li>
        </ul>

        <ul class="auth-block">
            <?if( 0 >= Dez::app()->auth->id() ):?>
                <li><a href="<?=adminUrl( 'index:login' )?>">Войти</a></li>
                <li><a href="<?=adminUrl( 'index:registration' )?>">Регистрация</a></li>
            <?else:?>
                <li><a href="<?=adminUrl( 'system:dashboard' )?>">В панель</a></li>
                <li><a href="<?=adminUrl( 'account:index' )?>"><b><?=Dez::app()->auth->get( 'email' )?></b></a></li>
                <li><a href="<?=adminUrl( 'index:logout' )?>">Выйти</a></li>
            <?endif;?>
        </ul>

    </header>

    <div class="box">

        <div class="left-block">
            <?=( isset( $left ) ? $left : null )?>
        </div>

        <div class="content fixed-height">
            <?= $layout->get( 'errorMessages' ); ?>
            <?= $layout->get( 'infoMessages' ); ?>
            <?= $layout->get( 'content' ); ?>
        </div>
        <div class="clr"></div>

    </div>

    <footer>

    </footer>

    </body>
</html>
