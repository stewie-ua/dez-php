<?php
    use Dez\Utils\HTML;
?>
<h1>Роли пользователей</h1>
<table class="main">
    <thead>
    <tr>
        <td>Название</td>
        <td>Действия</td>
    </tr>
    </thead>

    <tr>
        <td colspan="2" class="text-left">
            <?= HTML::a( adminUrl( 'users:roleCreate' ), 'Добавить', [ 'class' => 'a-button' ] ) ?>
        </td>
    </tr>

    <?foreach( $roles as $role ):?>
    <tr>
        <td>
            <b><?= $role->getName() ?></b>
        </td>
        <td>
            <?= HTML::a( adminUrl( 'users:roleEdit', [ 'id' => $role->id() ] ), 'Изменить', [ 'class' => 'a-button' ] ) ?>
        </td>
    </tr>
    <?endforeach;?>
</table>