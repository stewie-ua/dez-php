<?php

    namespace Dez\Web\Layout;

    trait HeadTrait {

        public function setTitle( $title = null ) {
            $this->set( self::KEY_NAME_TITLE, [ $title ] );
            return $this;
        }

        public function addTitle( $title = null ) {
            $this->add( self::KEY_NAME_TITLE, $title );
            return $this;
        }

        public function setDescription( $description = null ) {
            $this->set( self::KEY_NAME_DESCR, [ $description ] );
            return $this;
        }

        public function addDescription( $description = null ) {
            $this->add( self::KEY_NAME_DESCR, $description );
            return $this;
        }

        public function setKeyword( $keyword = null ) {
            $this->set( self::KEY_NAME_KEYWORD, [ $keyword ] );
            return $this;
        }

        public function addKeyword( $keyword = null ) {
            $this->add( self::KEY_NAME_KEYWORD, $keyword );
            return $this;
        }

    }