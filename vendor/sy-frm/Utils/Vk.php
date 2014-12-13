<?php
	
	namespace Sy\Util;
	
	class VK{
		
		private static $_config = array();
		private $_token	= false;
		
		public function __construct( array $config = array() ){
			self::$_config = array(
				'vkAppId' 	=> $config['app_id'],
				'vkLogin' 	=> $config['login'],
				'vkPasswd' 	=> $config['passwd'],
				'userAgent' => $config['ua'],
				'cacheDir' 	=> $config['cache_dir']
			);
		}
		
		// vk API
		public function search( $query, $page = 1 ){
			
			$count 		= 40;
			$offset		= ( $page - 1 ) * $count;			
			$requestURL = 'https://api.vk.com/method/audio.search?access_token=' . $this->getToken() . '&count='. $count .'&offset='. $offset .'&q=' . urlencode( $query ) . '&format=JSON';
			
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
			curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $curl, CURLOPT_URL, $requestURL );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
			
			$result = curl_exec( $curl );
			
			curl_close( $curl );
			
			return json_decode( $result );
			
		}
		
		public function getById( $aid ){
		
			$requestURL = 'https://api.vk.com/method/audio.getById?access_token=' . $this->getToken() . '&audios=' . $aid;
			
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
			curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $curl, CURLOPT_URL, $requestURL );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
			
			$result = curl_exec( $curl );
			
			curl_close( $curl );
			
			return $result;
			
		}
		
		// Connection
		public function getToken(){			
			$cachedToken = $this->_getCachedToket();			
			if( ! $cachedToken ){
				$this->_newToken();
				$this->_cacheToken();
				return $this->_token;	
			}else{
				return $cachedToken;
			}					
		}
		
		private function _newToken(){
			
			$cookieFile = $this->_getCookieFile();			
			
			// Get main auth form
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookieFile );
			curl_setopt( $curl, CURLOPT_COOKIEJAR, $cookieFile );
			curl_setopt( $curl, CURLOPT_USERAGENT, self::$_config['userAgent'] );
			curl_setopt( $curl, CURLOPT_URL, 'http://m.vk.com/login' );
			curl_setopt( $curl, CURLOPT_HEADER, 1 );
			$result = $this->_procCurl( $curl );
			curl_close( $curl );
			
			preg_match( '/&ip_h=(\w+)&/', $result, $match );			
			$ipHash = $match[1];
			
			// Submit login form
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookieFile );
			curl_setopt( $curl, CURLOPT_COOKIEJAR, $cookieFile );
			curl_setopt( $curl, CURLOPT_USERAGENT, self::$_config['userAgent'] );
			curl_setopt( $curl, CURLOPT_REFERER, 'http://m.vk.com/login' );
			curl_setopt( $curl, CURLOPT_URL, 'https://login.vk.com/?act=login&_origin=http://m.vk.com&ip_h='. $ipHash .'&role=pda&utf8=1' );
			curl_setopt( $curl, CURLOPT_HEADER, 1 );
			curl_setopt( $curl, CURLOPT_POST, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, 'email='. self::$_config['vkLogin'] .'&pass=' . self::$_config['vkPasswd'] );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );				
			$result = $this->_procCurl( $curl );
			curl_close( $curl );
			
			// OAuth
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_HEADER, 1 );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_NOBODY, 0 );
			curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookieFile );
			curl_setopt( $curl, CURLOPT_COOKIEJAR, $cookieFile );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $curl, CURLOPT_URL, 'http://oauth.vk.com/authorize?client_id='. self::$_config['vkAppId'] .'&scope=audio&response_type=token' );
			$result = $this->_procCurl( $curl );						
			curl_close( $curl );
			
			if( ! $this->_token ){
				// Approve app if not approved yet
				$this->_approveApp( $result, $cookieFile );
			}

			unlink( $cookieFile );
			
		}
		
		private function _cacheToken(){
			if( ! file_exists( self::$_config['cacheDir'] ) ){
				@ mkdir( self::$_config['cacheDir'], 0777, true );
			}
			$cacheFile = self::$_config['cacheDir'] . '/vk_token_' . md5( self::$_config['vkLogin'] ) . '.dat';
			return file_put_contents( $cacheFile, $this->_token );
		}
		
		private function _getCachedToket(){
			if( ! file_exists( self::$_config['cacheDir'] ) ){
				@ mkdir( self::$_config['cacheDir'], 0777, true );
			}
			$cacheFile = self::$_config['cacheDir'] . '/vk_token_' . md5( self::$_config['vkLogin'] ) . '.dat';
			if( ! file_exists( $cacheFile ) || ( filemtime( $cacheFile ) + 84600 ) <= time() ){
				return false;
			}else{
				return file_get_contents( $cacheFile );
			}
		}
		
		private function _getCookieFile(){
			if( ! file_exists( self::$_config['cacheDir'] ) ){
				@ mkdir( self::$_config['cacheDir'], 0777, true );
			}
			return self::$_config['cacheDir'] . '/vk_auth_cookies_' . md5( self::$_config['vkLogin'] ) . '.dat';
		}
		
		private function _procCurl( $curl, $dump = 0 ){
			curl_setopt( $curl, CURLOPT_HEADER, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			$result 	= curl_exec( $curl );			
			$info 		= curl_getinfo( $curl );
			$httpCode	= $info['http_code'];
			
			if( in_array( $httpCode,  array( 301, 302 ) ) ){
				$redirectURL = $info['redirect_url'];
				
				if( preg_match( '/access_token=(\w+)&expires_in=86400/', $redirectURL, $match ) ){
					$this->_token = trim( $match[1] );
					return;
				}
				
				curl_setopt( $curl, CURLOPT_URL, $redirectURL );								
				return $this->_procCurl( $curl, $dump );
			}else{				
				$result = explode( "\r\n\r\n", $result, 2 );
				if( isset( $result[1] ) and $httpCode == 200 ){
					return $result[1];
				}else{
					return false;
				}
			}			
		}
		
		private function _approveApp( $result, $cookieFile ){
			if( preg_match( '/"(https:\\/\\/login\.vk\.com\\/\\?act=grant_access[&=_\w]+)"/', $result, $match ) ){
				$url 	= trim( $match[1] );				
				$curl 	= curl_init();
				curl_setopt( $curl, CURLOPT_HEADER, 1 );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $curl, CURLOPT_NOBODY, 0 );
				curl_setopt( $curl, CURLOPT_COOKIEFILE, $cookieFile );
				curl_setopt( $curl, CURLOPT_COOKIEJAR, $cookieFile );
				curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
				curl_setopt( $curl, CURLOPT_URL, $url );
				$result = $this->_procCurl( $curl, 6 );				
			}else{
				return true;
			}
		}
		
	}