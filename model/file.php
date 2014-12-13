<?php

    use \Sy\Core,
        \Sy\Error\Error,
        \Sy\Common\Validator;

    class FileModel extends Core\Model {

        public function uploadImage() {
            $file   = $this->request->file( 'file' );

            $app    = \Sy::app();
            $fso    = new \Sy\Utils\FSO();
            $string = new \Sy\Utils\String();
            $image  = new \Sy\Utils\Image();

            $fileName       = rand( 100, 999 ) . time() .'_'. $string->transliteration( $file['name'], true );
            $avatarDir      = $app->config->path( 'app/upload/image_dir' )
                . DS . date( 'Ymd_H' );
            $fullFilePath   = $avatarDir . DS . $fileName;

            try {
                $fso->create( APP_PATH . DS . $avatarDir );
                try {
                    try {
                        $image->load( $file['tmp_name'] );
                        $maxSize = $app->config->path( 'app/upload/image_max_size', 800 );
                        if( $image->getWidth() < $image->getHeight() ) {
                            if( $image->getHeight() > $maxSize )
                                $image->resizeToHeight( $maxSize );
                        } else {
                            if( $image->getWidth() > $maxSize )
                                $image->resizeToWidth( $maxSize );
                        }
                        $image->save( $fullFilePath, IMAGETYPE_JPEG, 100 );
                    } catch ( \Exception $e ) {
                        throw $e;
                    }
                } catch ( \Exception $e ) {
                    throw $e;
                }
            } catch ( \Exception $e ) {
                throw $e;
            }

            return $fullFilePath;
        }

    }