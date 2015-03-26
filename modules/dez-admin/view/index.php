<?php
use Dez\Core\Url, Dez\Core\UrlBuilder, Dez\Web;
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
    <body>

    <header>

        <div class="logo">dezAdmin</div>

        <ul class="menu">
            <li><a href="<?=adminUrl( 'system:dashboard' )?>">Главная</a></li>
            <li><a href="<?=adminUrl( 'users:index' )?>">Пользователи</a></li>
            <li><a href="<?=adminUrl( 'modules:index' )?>">Модули</a></li>
            <li><a href="<?=adminUrl( 'tableGenerator:create' )?>">Генератор таблиц</a></li>
            <li><a href="<?=adminUrl( 'fileManager:index' )?>">Файловый менеджер</a></li>
            <li><a href="<?=adminUrl( 'systemTools:index' )?>">Инструменты</a></li>
        </ul>

        <ul class="auth-block">
            <li><a href="<?=adminUrl( 'account:index' )?>"><b><?=Dez::app()->auth->get( 'email' )?></b></a></li>
            <li><a href="<?=adminUrl( 'index:logout' )?>">Выйти</a></li>
        </ul>

    </header>

    <div class="box">

        <div class="left-block">
            <?= $layout->get( 'left' ); ?>
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
