<?php

	namespace Sy\Util;
	
	class StringFilter{
		
		public function toUnderline( $string ){
			
		}
		
		public function deleteBad( $string ){			
			$string = preg_replace( '/[^a-zа-яàáâãäåçèéêëìíîïðñòóôõöøùúûÜёъьєії\d\s-_]+/ui', '', $string );	
			$string = trim( $string );
			return $string;
		}
		
		public function musicFilter( $string ){
			
		}
		
		public function clear( $string ){
			$chuncks 	= array();
			$chuncks 	= preg_split( '/feat|ft|vs|&amp;|&/ui', $string, 2, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
			
			$string 	= trim( $chuncks[0] );			
			$string 	= $this->deleteBad( $string );			
			$string		= strtolower( $string );
			
			$string 	= $this->wordToUppercase( $string );
			
			return $string;			
		}
		
		public function wordToUppercase( $string ){
			return mb_convert_case( $string, MB_CASE_TITLE, 'UTF-8' );
		}
		
	}