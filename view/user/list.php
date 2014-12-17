<?php
    use Dez\Utils\HTML,
        Dez\Core\UrlBuilder as Url;
?>
<h1>Пользователи</h1>
<table class="main">
    <thead>
    <tr>
        <td>Email</td>
        <td>Логин</td>
        <td>Зарегестрирован</td>
        <td>Действия</td>
    </tr>
    </thead>
    <?foreach( $users as $user ):?>
        <tr>
            <td><?=$user->getEmail()?></td>
            <td><?=$user->getLogin()?></td>
            <td class="center"><?=$user->registerDate()?></td>
            <td class="center">
                <?=HTML::a(
                    url( 'users/'. $user->id() .'/edit' ),
                    'Изменить',
                    [ 'class' => 'a-button' ]
                )?>
                <?=HTML::a(
                    url( 'users/'. $user->id() .'/block' ),
                    'Блокировать',
                    [ 'class' => 'a-button' ]
                )?>
                <?=HTML::a(
                    url( 'users/'. $user->id() .'/delete', [] ),
                    'Удалить',
                    [
                        'class'     => 'a-button a-button-hover-red',
                        'onclick'   => 'return confirm( \'Delete user ID:'. $user->id() .' ?\' );'
                    ]
                )?>
            </td>
        </tr>
    <?endforeach;?>
</table>

<?=\Helper\Common::pagi( $users->getPagi() )?>