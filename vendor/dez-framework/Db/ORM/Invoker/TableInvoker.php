<?php

    namespace Dez\ORM\Invoker;

    trait TableInvoker {

        use Invoker;

        protected function getMethodList() {
            return [ 'filterBy', 'groupBy', 'orderBy', 'limit', 'use' ];
        }

    }