<?php
namespace WScore\Template;

/**
 * Template engine using pure PHP code.
 */

class PhpTemplate implements TemplateInterface
{
    /** @var string  */
    protected $templateFile;

    /** @var string[] */
    protected $parentTemplates = array();

    protected $self = false;

    /** @var array */
    protected $data = array();

    /** @var string root folder of templates.  */
    protected $rootDir = null;
    
    /** @var bool|string */
    protected $contentFilter = true; 

    /**
     * @Inject
     * @var \WScore\Template\Filter
     */
    public $filter;

    // +----------------------------------------------------------------------+
    /**
     */
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @return TemplateInterface
     */
    public function setTemplate( $name ) {
        $this->templateFile = $this->getTemplateFile( $name );
        return $this;
    }

    /**
     * @param $root
     * @return TemplateInterface
     */
    public function setRoot( $root ) {
        if( !$root ) {
            $this->rootDir = '';
        }
        else {
            $this->rootDir = $root;
            if( substr( $this->rootDir, -1 ) !== '/' ) {
                $this->rootDir .= '/';
            }
        }
        return $this;
    }

    /**
     * sets parent/outer template for the current template.
     *
     * @param string $parentTemplate
     * @return TemplateInterface
     */
    public function setParent( $parentTemplate ) {
        $this->parentTemplates = array( $parentTemplate );
        return $this;
    }

    /**
     * @param $parentTemplate
     * @return PhpTemplate
     */
    public function addParent( $parentTemplate ) {
        array_push( $this->parentTemplates, $parentTemplate );
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getTemplateFile( $name )
    {
        if( substr( $name, 0, 2 ) === './' ) {
            // referencing with respect to the current template.
            return dirname( $this->templateFile ) . substr( $name, 1 );
        }
        elseif( substr( $name, 0, 3 ) === '../' ) {
            // referencing upward folder.
            return dirname( $this->templateFile ) . $name;
        }
        elseif( substr( $name, 0, 1 ) === '/' ) {
            // it's an absolute path for *nix system.
            return $name;
        }
        elseif( preg_match( '/^[a-zA-Z]{1}:/', $name ) ) {
            // it's an absolute path for Windows system.
            return $name;
        }
        return $this->rootDir . $name;
    }
    // +----------------------------------------------------------------------+
    //  setting values.
    // +----------------------------------------------------------------------+
    /**
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function set( $name, $value ) {
        $this->data[ $name ] = $value;
    }

    /**
     * sets a default value, or sets value if it's not set.
     *
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function setDefault( $name, $value ) {
        if( !isset( $this->data[ $name ] ) ) {
            $this->data[ $name ] = $value;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set( $name, $value ) {
        $this->set( $name, $value );
    }

    /**
     * mass assign data.
     *
     * @param array $data
     * @return void
     */
    public function assign( $data ) {
        $this->data = array_merge( $this->data, $data );
    }

    // +----------------------------------------------------------------------+
    //  getting values.
    // +----------------------------------------------------------------------+
    /**
     * @param string $name
     * @param mixed  $default
     * @return null|mixed
     */
    public function get( $name, $default=null ) {
        list( $name, $filters ) = $this->parse( $name );
        if( !array_key_exists( $name, $this->data ) ) return $default;
        return $this->filter( $this->data[ $name ], $filters );
    }

    /**
     * @param $method
     * @param $args
     * @return mixed|null
     */
    public function __call( $method, $args ) {
        $name = array_shift( $args );
        list( $name, $filters ) = $this->parse( $name );
        if( !$value = $this->get( $name ) ) return $value;
        return $this->filter( $value, $filters, $method );
    }

    /**
     * @param $name
     * @return array
     */
    protected function parse( $name ) {
        $list = explode( '|', $name );
        $name = array_shift( $list );
        return array( $name, $list );
    }

    /**
     * @param        $value
     * @param        $filters
     * @param string $method
     * @return mixed
     */
    protected function filter( $value, $filters, $method='' )
    {
        $value = $this->filter->apply( $value, $filters, $method );
        return $value;
    }

    /**
     * html safe get.
     *
     * @param $name
     * @return string
     */
    public function __get( $name ) {
        return $this->get( $name.'|h' );
    }

    /**
     * @param string $name
     * @param array|mixed $default
     * @return mixed|null
     */
    public function arr( $name, $default=array() ) {
        return $this->get( $name, $default );
    }

    // +----------------------------------------------------------------------+
    //  rendering the template.
    // +----------------------------------------------------------------------+
    /**
     *
     * @return mixed
     */
    public function render()
    {
        $content = $this->renderer( $this->templateFile );
        $content = $this->filterContent( $content );
        $content = $this->renderParent( $content );

        return $content;
    }

    private function renderParent( $content )
    {
        if( $this->parentTemplates ) {
            $this->set( 'content', $content );
            $parentTemplate = array_pop( $this->parentTemplates );
            $parent = clone $this;
            $parent->self = false;
            $parent->setTemplate( $parentTemplate );
            $content = $parent->render();
        }
        return $content;
    }
    
    private function filterContent( $content )
    {
        if( $this->contentFilter === false ) {
            // contents filter is off. do nothing. 
        }
        elseif( $this->contentFilter === true ) {
            // automatic content filter based on file name. 
            if( $filters = $this->getContentFilter() ) {
                $content = $this->filter->apply( $content, $filters );
            }
        }
        return $content;
    }
    
    private function getContentFilter()
    {
        $filename  = pathinfo( $this->templateFile, PATHINFO_BASENAME );
        $extensions = explode( '.', $filename );
        array_shift( $extensions );
        $filters = array(
            'txt'      => 'pre',
            'text'     => 'pre',
            'md'       => 'md',
            'markdown' => 'md',
        );
        foreach( $filters as $ext => $f ) {
            if( in_array( $ext, $extensions ) ) {
                return array( $f );
            }
        }
        return null;
    }
    
    /**
     * @param string $__template__
     * @return string
     */
    private function renderer( $__template__ )
    {
        ob_start();
        require $__template__;
        return ob_get_clean();
    }

    /**
     * renders a block: rendering without parent.
     *
     * @param $name
     * @param $blockName
     * @return mixed
     */
    public function block( $name, $blockName )
    {
        // clone $this. for block should not influence the caller template. 
        $view = clone( $this );
        $view->setTemplate( $blockName );
        $view->parentTemplates = null;
        $this->data[ $name ]  = $content = $view->render();
        return $content;
    }
    /**
     * rendering output from own php file.
     */
    public function renderSelf() {
        $this->self = true;
        ob_start();
    }

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->render( $this->templateFile );
    }
    
    public function __destruct() {
        if( $this->self ) {
            $content = ob_get_clean();
            $content = $this->renderParent( $content );
            echo $content;
        }
    }
    // +----------------------------------------------------------------------+
}