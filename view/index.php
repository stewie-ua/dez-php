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
<body>

    <header>

        <div class="logo">DevSite</div>

        <ul class="menu">
            <li><a href="<?=url( '/' )?>">Главная</a></li>
            <li><a href="<?=url( 'tools' )?>">Инструменты</a></li>
            <li><a href="<?=url( 'posts' )?>">Статьи</a></li>
            <li><a href="<?=url( 'feedback' )?>">Обратная связь</a></li>
            <li><a href="<?=url( 'about' )?>">Обо мне</a></li>
        </ul>

        <ul class="auth-block">
            <?if( ! Dez::app()->auth->isLogged() ):?>
                <li><a href="<?=url( 'auth/login', [ 'return_url' => Url::current() ] )?>">Login</a></li>
                <li><a href="<?=url( 'auth/registration' )?>">Registration</a></li>
            <?else:?>
                <li><a href="<?=url( 'profile' )?>"><b><?=Dez::app()->auth->get( 'email' )?></b></a></li>
                <li><a href="<?=url( 'auth/logout' )?>">Logout</a></li>
            <?endif;?>
        </ul>

    </header>

    <div class="box">

        <div class="left-block">
            <?= callModule( 'common/leftmenu' ) ?>
            <?=( isset( $left ) ? $left : null )?>
        </div>

        <div class="content fixed-height">
            <?= $layout->get( 'content' ); ?>
        </div>
        <div class="clr"></div>

    </div>

    <footer>

    </footer>

    <?php print empty( $debug_block ) ? null : $debug_block ?>

</body>
</html>
