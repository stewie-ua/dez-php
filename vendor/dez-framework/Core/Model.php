<?php

	namespace Dez\Core;

	class Model extends Object {

        protected
            $db         = null,
            $request    = null;

		public function __construct(  ) {
            $this->db       = \Dez::app()->db;
            $this->request  = \Dez::app()->request;
		}

	}