<?php

	// Ivan Gontarenko
	
	namespace Dez\Util;
	
	class C36{

		private $_list = array(),
				$_size = 0;

		public function __construct(){
			$this->_list = array_merge( array(-1), range( 'a', 'z' ), range( '0', '9' ) );
			unset( $this->_list[0] );
			$this->_size = count( $this->_list );
		}

		public function encode( $int_value = 0 ){

			if( ! is_numeric( $int_value ) || 0 >= $int_value ){
				return null;
			}

			$l = 1;
			$r = '';

			$tmp_value = $int_value;
			do{
				$tmp_value /= $this->_size;
				$l++;
			}while( $tmp_value > $this->_size );

			for( $j = $l; $j > 0; $j-- ){
				$p = ( $j == 1 ) ? 1 : 0;
				$s = pow( $this->_size, $j - 1 );
				while( $int_value > $s && ( ( $j - 2 ) <> 1 ? true : ( ( $int_value - $s ) > $this->_size ) ) ){					
					$int_value -= $s; $p++;										
				}
				$r .= $this->_list[$p];				
			}

			return $r;

		}

		public function decode( $str_value = '' ){

			$str_value = (string) $str_value;

			if( ! is_string( $str_value ) ){
				return null;
			}

			$l = strlen( $str_value );
			$r = 0;

			for( $i = $l-1; $i >= 0; $i-- ){
				$c = $str_value{$i};
				$k 	= array_search( $c, $this->_list );
				if( $l - 1 == $i ){
					$r += $k;
				}else{
					$r += pow( $this->_size, ( $l - 1 ) - $i ) * $k;
				}
			}

			return $r;

		}

	}