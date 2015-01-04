<?php

    namespace Dez\Web;

    use Dez\Core\HasDataTrait,
        Dez\Error\Exception\RuntimeError,
        Dez\Core\SingletonTrait,
        Dez\View\View,
        Dez\Web\Layout\HeadTrait,
        Dez\Web\Layout\LayoutData;

    class Layout extends View {

        use HasDataTrait, HeadTrait;

        const
            KEY_NAME_TITLE      = 'head:title',
            KEY_NAME_KEYWORD    = 'head:keyword',
            KEY_NAME_DESCR      = 'head:description';

        protected
            $name           = 'index',
            $view           = null,
            $asset          = null,
            $content        = null,
            $data           = [];

        public function __clone() { throw new RuntimeError( 'Cloning of the object forbidden' ); }

        protected function init() {
            $this->asset = Asset::instance();
        }

        public function setName( $name = null ) {
            $this->name = $name;
            return $this;
        }

        public function getName() {
            return $this->name;
        }

        public function getContent() {
            return $this->content;
        }

        public function setContent( $content = null ) {
            $this->content  = $content;
            return $this;
        }

        public function css( $css ) {
            $this->asset->addCSS( $css );
            return $this;
        }

        public function js( $js ) {
            $this->asset->addJS( $js );
            return $this;
        }

        public function output() {
            $this->set( 'head',     Asset::render() );
            $this->set( 'content',  $this->content );
            return parent::render( $this->getName(), [
                'layout'    => LayoutData::instance( $this->getData() )
            ] );
        }

        protected function & getData() { return $this->data; }

    }