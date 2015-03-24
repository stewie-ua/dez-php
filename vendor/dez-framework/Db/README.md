## Dez ORM Examples

### Extend Table Model

    use Dez\ORM\Model\Table as TableModel;

    include_once 'ORM.php';

    class UserModel extend TableModel {
        // set a table name of UserModel
        static $table = 'users';
    }

    // collection of UserModel
    $users = UserModel::all();

    // set for all users name as 'John'
    foreach( $users as $user ) {
        // method setUserName mean a set for users table column name `user_name`
        $user->setUserName( 'John' );
    }

    // or use method from collection
    $users->each( function( $i, $user ) {
        $user->setUserName( 'John' );
    } );

    // and update all users
    $users->save();