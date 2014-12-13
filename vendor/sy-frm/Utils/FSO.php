<?php
	
	namespace Sy\Utils;
	
	class FSO {
		
		public function put( $file, $content ){
			$dir = dirname( $file );

			try{
				$this->create( $dir );
				if( $size = file_put_contents( $file, $content ) ){
					return $size;
				}else{
					throw new Exception( 'Can`t put in file ['. __METHOD__ .']' );
				}
			}catch( \Exception $e ){
				throw new \Exception( $e );
			}
		}

		public function create( $dir_path ){
			if( ! $this->isExistsDir( $dir_path ) ){
				if( ! @ mkdir( $dir_path, 0777, true ) ){
					if( defined( 'DEBUG' ) && DEBUG === true ){
						throw new \Exception( 'Can`t create directory ['. $dir_path .']' );
					}else{
						throw new \Exception( 'Can`t create directory' );
					}
				}else{
					return true;
				}
			}
		}

        public function isExistsDir( $dir = '/' ){
            return ( file_exists( $dir ) && is_dir( $dir ) );
        }

        public function isExistsFile( $file_path = '/' ){
            return ( file_exists( $file_path ) && is_file( $file_path ) );
        }

        public function chmodIs( $target = null, $compareChmod = 655 ){
            return ( $this->getPermissions( $target ) == $compareChmod );
        }

        // return like 755, 777
        public function getPermissions( $target = null ){
            clearstatcache();
            $permission = substr( decoct( fileperms( $target ) ), 2 );
            return $permission;
        }

        public function fileExt( $filename ) {
            $chunks = explode( '.', $filename );
            return end( $chunks );
        }

        public function move( $fileFrom, $fileTo ) {
            $this->put( $fileTo, file_get_contents( $fileFrom ) );
        }
		
	}