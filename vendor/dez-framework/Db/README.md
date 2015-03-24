## DezORM

### Создание модели таблицы

```php

    class UserModel extend TableModel {
        // установите имя таблицы базы данных
        // Обязательный параметр
        static $table = 'users';
    }

    // получаем коллекцию моделей UserModel
    $users = UserModel::all();

    // к примеру надо установить всем пользователям одно и тоже имя
    foreach( $users as $user ) {
        // назначаем имя Джон для поля user_name
        $user->setUserName( 'Джон' );
    }

    // также можно использовать методы коллекции для прохождений по каждой модели
    $users->each( function( $i, $user ) {
        $user->setUserName( 'John' );
    } );

    // обновляем всех пользователей
    $users->save();

```