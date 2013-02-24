<?php
namespace WScore\Template;

/**
 * Template engine using pure PHP code.
 */

class PhpTemplate implements TemplateInterface
{
    /** @var string  */
    protected $templateFile;

    /** @var Template */
    protected $parentTemplate = null;

    /** @var array */
    protected $data = array();

    /**
     * @Inject
     * @var \WScore\Template\Filter
     */
    public $filter;

    /**
     * @Inject
     * @var \WScore\Template\Renderer
     */
    public $renderer;
    
    // +----------------------------------------------------------------------+
    /**
     */
    public function __construct()
    {
    }

    public function setTemplate( $name ) {
        $this->templateFile = $name;
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

    /**
     * sets parent/outer template for the current template.
     *
     * @param string $parentTemplate
     * @return void
     */
    public function setParent( $parentTemplate ) {
        $this->parentTemplate = $parentTemplate;
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
     * @param null|string $template
     * @return mixed
     */
    public function render( $template=null )
    {
        $content = $this->renderer->render( $this->templateFile, $this->data, $this );

        if( $this->parentTemplate ) {
            $this->set( 'content', $content );
            $parent = clone $this;
            $this->setTemplate( $this->parentTemplate );
            $parent->assign( $this->data );
            $this->setParent( null );
            $content = $this->render();
        }

        return $content;
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
        $view->templateFile   = $blockName;
        $view->parentTemplate = null;
        $this->data[ $name ]  = $content = $view->render();
        return $content;
    }
    /**
     * rendering output from own php file.
     */
    public function renderSelf() {
        $this->renderer->renderSelf();
    }

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->render( $this->templateFile, $this->data );
    }
    
    public function __destruct() {
        unset( $this->renderer );
    }
    // +----------------------------------------------------------------------+
}