<?php

    namespace Dez\ORM\Relation;

    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;

    abstract class Relation extends Object {

        use SingletonTrait;

        protected
            $collection = null;

        public function get() {
            return $this->collection;
        }

    }