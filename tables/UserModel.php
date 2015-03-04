<?php

    namespace DB;

    use Dez\ORM\Model\Table;

    class UserModel extends Table {

        static protected
            $table  = 'system_auth';

        public function session() {
            return $this->hasOne( '\DB\SessionModel', 'user_id' );
        }

        public function sessions() {
            return $this->hasMany( '\DB\SessionModel', 'user_id' );
        }

    }