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
                    adminUrl( 'users:profile', [ 'id' => $user->id() ] ),
                    'Изменить',
                    [ 'class' => 'a-button' ]
                )?>
                <?=HTML::a(
                    adminUrl( 'users:update', [ 'id' => $user->id(), 'do' => 'block' ] ),
                    'Блокировать',
                    [ 'class' => 'a-button' ]
                )?>
                <?=HTML::a(
                    adminUrl( 'users:update', [ 'id' => $user->id(), 'do' => 'delete' ] ),
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