<?php
    use \Dez\Core\UrlBuilder as Url;
?>
<ul>
    <li><a href="<?= url( Url::c( 'user:list', [1] ) ) ?>">Пользователи</a></li>
</ul>