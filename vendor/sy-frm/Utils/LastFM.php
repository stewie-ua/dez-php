<?php

	namespace Sy\Util;

	class LastFM_API{
		
		private $api_key = null;
		
		static private $lastfm_url = 'http://ws.audioscrobbler.com/2.0/';
		
		public function __construct( $api_key ) {
			$this->api_key = $api_key;
		}
		
		public function method( $method_name, $q ){
			$vars = array(
				'method'	=> $method_name,
				'api_key'	=> $this->api_key,
				'format'	=> 'json',
				'lang'		=> 'ru'
			);
			$vars 	= array_merge( $vars, $q );
			$url 	= self::$lastfm_url . '?' . http_build_query( $vars );				
			return static::_request( $url );			
		}
		
		public function artist( $do, $q ){
			$method = 'artist.' . strtolower( trim( $do ) );
			return $this->method( $method, $q );
		}
		
		public function getInfo( $artist ){
			
			$method 	= 'artist.getInfo';
			$cacheKey	= $method . '.' . $artist;
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'artist' => $artist ) );
				$data		= array();
				
				if( property_exists( $response, 'artist' ) ){
					$response 		= $response->artist;
					
					$data['name'] 			= $response->name;
					$data['images'] 		= array();
					$data['tags'] 			= array();
					$data['similar']		= array();
					$data['description']	= array();
					
					foreach( $response->image as $image ){
						$image = (array) $image;
						$data['images'][]	=  $image['#text'];
					}
					
					if( is_object( $response->similar ) ){
						$similar = array();
						foreach( $response->similar->artist as $similarArtist ){
							$similarArtist	= (array) $similarArtist;
							$similarImages 	= array();
							
							foreach( $similarArtist['image'] as $image ){
								$image 				= (array) $image;
								$similarImages[]	= $image['#text'];
							}
							
							$similar[]		= array(
								'name' 		=> $similarArtist['name'],
								'images'	=> $similarImages
							);
						}
						$data['similar'] = $similar;
					}
					
					if( is_object( $response->tags ) ){
						foreach( $response->tags->tag as $tag ){
							$data['tags'][] 	= array(
								'title'	=> ucwords( trim( $tag->name ) ),
								'name'	=> preg_replace( '/\s+/', '+', trim( $tag->name ) )
							);
						}
					}
					
					if( is_object( $response->bio ) ){
						$data['description'] = trim( trim( $response->bio->summary ), "\s\n" );
					}
					
					try{
						$cache->write( $data );
					}catch( Exception $e ){
						throw $e;
					}					
				}
			}
			
			return $data;
		}
		
		public function topArtists(){
			$method 	= 'chart.getTopArtists';
			$cacheKey	= $method . '.top';
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array() );
				$data		= array();
				
				if( property_exists( $response, 'artists' ) ){
					$response = $response->artists;
					if( property_exists( $response, 'artist' ) ){
						$response = $response->artist;
						if( is_array( $response ) && ! empty( $response ) ){
							foreach( $response as $item ){
								$item 	= (array) $item;
								$artist = array(
									'name'		=> $item['name'],
									'images'	=> array()
								);
								$images = $item['image'];
								foreach( $images as $image ){
									$image 					= (array) $image;
									$artist['images'][]		= $image['#text'];
								}
								$data[] = $artist;
							}
						}
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		public function getSimilar( $artist ){
			$method 	= 'artist.getSimilar';
			$cacheKey	= $method . '.' . md5( $artist );
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'artist' => $artist ) );
				$data		= array();
				
				if( property_exists( $response, 'similarartists' ) ){
					$response = $response->similarartists;
					
					if( ! empty( $response->artist ) ){						
						foreach( $response->artist as $similarArtist ){
						
							$similarArtist	= (array) $similarArtist;
							$similarImages 	= array();
							
							foreach( $similarArtist['image'] as $image ){
								$image 				= (array) $image;
								$similarImages[]	= $image['#text'];
							}
							
							$data[]		= array(
								'name' 		=> $similarArtist['name'],
								'images'	=> $similarImages
							);
						}
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;			
		}
		
		public function getTopArtistsByTag( $tag ){
			$method = 'tag.getTopArtists';
			$cacheKey	= $method . '.' . $tag;
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'tag' => $tag ) );
				$data		= array();
				
				if( property_exists( $response, 'topartists' ) ){
					$response = $response->topartists;
					
					if( ! empty( $response->artist ) ){
					
						if( ! is_array( $response->artist ) ){
							$response->artist = array( $response->artist );
						}
						
						foreach( $response->artist as $artist ){
						
							$artist			= (array) $artist;
							$artistImages 	= array();
							
							if( ! empty( $artist['image'] ) ){
								foreach( $artist['image'] as $image ){
									$image 				= (array) $image;
									$artistImages[]		= $image['#text'];
								}
							}
														
							$data[]		= array(
								'name' 		=> $artist['name'],
								'images'	=> $artistImages
							);
						}
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		public function getTagInfo( $tag ){
			$method 	= 'tag.getInfo';
			$cacheKey	= $method . '.' . $tag;
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'tag' => $tag ) );
				$data		= array();
				
				if( property_exists( $response, 'tag' ) ){
					$response 		= $response->tag;
					$data['name']	= $response->name;
					if( is_object( $response->wiki ) ){
						$data['description'] = trim( trim( $response->wiki->summary ), "\s\n" );
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		public function getTags( $artist ){
			$method 	= 'artist.getTags';
			$cacheKey	= $method . '.' . $artist;
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'artist' => $artist ) );
				$data		= array();

				if( property_exists( $response, 'tag' ) ){
					$response 		= $response->tag;
					$data['name']	= $response->name;
					if( is_object( $response->wiki ) ){
						$data['description'] = trim( trim( $response->wiki->summary ), "\s\n" );
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		public function getTopTags(){
			$method 	= 'tag.getTopTags';
			$cacheKey	= $method . '.top';
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array() );
				$data		= array();
				
				if( property_exists( $response, 'toptags' ) ){
					$response 		= $response->toptags;					
					foreach( $response->tag as $tag ){
						$data[] = array(
							'name'	=> ucwords( trim( $tag->name ) ),
							'title'	=> preg_replace( '/\s+/', '+', trim( $tag->name ) )
						);
					}
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		public function getSimilarTags( $tag ){
			$method 	= 'tag.getSimilar';
			$cacheKey	= $method . '.' . $tag;
			$cache		= new \Sy\Cache( 'lastfm.queries.'. $cacheKey );
			
			if( $cache->check() == true ){
				$data = $cache->get();
			}else{
				$response 	= $this->method( $method, array( 'tag' => $tag ) );
				$data		= array();
				
				if( property_exists( $response, 'similartags' ) ){
					$response 		= $response->similartags;
					
					if( is_array( $response->tag ) && ! empty( $response->tag ) ){
						foreach( $response->tag as $tag ){
							$data[] = array(
								'title'	=> ucwords( trim( $tag->name ) ),
								'name'	=> preg_replace( '/\s+/', '+', trim( $tag->name ) )
							);
						}
					}					
				}
				
				try{
					$cache->write( $data );
				}catch( Exception $e ){
					throw $e;
				}
			}
			
			return $data;
		}
		
		private function _request( $url ){
			$curl = curl_init();			
			curl_setopt( $curl, CURLOPT_HEADER, 0 );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 5 );
			curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
			curl_setopt( $curl, CURLOPT_URL, $url );	
			
			$data = curl_exec( $curl );
			curl_close( $curl );
			$data = json_decode( $data );				
					
			return $data;			
		}
		
	}
