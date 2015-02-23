<?php

    namespace Dez\ORM\Association;

    use Dez\ORM\Common\Object,
        Dez\ORM\Common\SingletonTrait,
        Dez\ORM\Entity\Table as TableQuery;

    class Association extends Object {

        use SingletonTrait;

        protected
            $queryBuilder = null;

        protected function init( $tableQuery = null ) {
            if( $tableQuery instanceof TableQuery ) {
                $this->queryBuilder = $tableQuery->getQueryBuilder();
            }
        }

    }