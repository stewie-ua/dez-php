<?php

    namespace DB;

    use Dez\ORM\Model\Table;

    class UserModel extends Table {

        static protected
            $table  = 'system_auth';

        public function sessions() {
            return $this->hasOne( '\DB\SessionModel', 'user_id' );
        }

    }