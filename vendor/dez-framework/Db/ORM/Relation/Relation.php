<?php

    namespace Dez\ORM\Relation;

    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;
    use Dez\ORM\Model\Table as TableModel;

    abstract class Relation extends Object {

        use SingletonTrait;

        protected
            $model          = null,
            $related        = null,
            $foreignKey     = 'id',

            $collection     = null;

        /**
         * @param   TableModel $model
         * @param   TableModel $related
         * @param   string $foreignKey
         */

        protected function init( $model, $related, $foreignKey ) {
            $this->model        = $model;
            $this->related      = $related;
            $this->foreignKey   = $foreignKey;
            $this->makeRelation();
        }

        abstract protected function makeRelation();

        public function get() {
            return $this->collection->findAll( function( $item ) {
                $id     = $this->foreignKey == $item->pk() ? $item->id() : $item->get( $this->foreignKey ) ;
                return $this->model->id() == $id;
            } );
        }

    }