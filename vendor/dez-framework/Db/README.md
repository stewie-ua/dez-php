# DezORM

## CRUD

### SELECT

#### Выбор всех записей из таблицы

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

#### Выбор с условиями

```php

    // объект составляющий запрос для результата
    $usersQueryBuilder = UserModel::query();

    // find - возвращает коллекцию моделей результата
    // в результате был выполнен следующий запрос
    //      SELECT `users`.*
    //      FROM `users`
    //      WHERE `users`.`status` = 'active'
    //      AND `users`.`last_visit` < '2015-01-01 01:01:59'
    //      ORDER BY `users`.`user_name` DESC
    $users = $usersQueryBuilder
        ->whereStatus( 'active' )
        ->whereLastVisit( ( new \DateTime( '- 15 days' )->format( 'Y-m-d H:i:s' ) ), '<' )
        ->orderUserName( 'DESC' )
        ->find();

```

### INSERT

#### Способ 1

```php

    $data   = [
        'user_name'     => 'John',
        'password'      => md5( 'user_password' )
    ];

    $user   = UserModel::insert( $data );

    die( var_dump( $user->id() ) );

```

#### Способ 2

```php

    $user   = new UserModel();

    $user
        ->setUserName( 'John' )
        ->setPassword( md5( 'user_password' ) )
        ->save();

    die( var_dump( $user->id() ) );

```