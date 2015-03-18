<?php

    namespace Dez\Auth;

    interface AuthInterface {

        public function authenticate();

        public function logout();

        public function id();

    }