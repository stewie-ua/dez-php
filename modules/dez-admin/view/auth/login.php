<?php
    use Dez\Core\UrlBuilder as UB;
?>
<h1>Вход в админ-панель</h1>
<div class="auth-login form">
    <form action="<?= adminUrl( UB::c( 'index:login' ) ) ?>" method="post">
        <div>
            <label for="email">E-mail:
                <input name="email" type="text" id="email" placeholder="email@example.com" value=""/>
            </label>

        </div>
        <div>
            <label for="password">Пароль:
                <input name="password" type="password" id="password" placeholder="Введите пароль" value=""/>
            </label>
        </div>
        <input type="submit" value="Войти"/>
    </form>
</div>