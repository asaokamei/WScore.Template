<?php
namespace WScore\Template;

interface TemplateInterface
{
    /**
     * sets a template file. 
     * 
     * @param string $name
     * @return mixed
     */
    public function setTemplate( $name );

    public function setRoot( $root );

    /**
     * sets parent template file.
     *
     * @param $parentTemplate
     * @return mixed
     */
    public function setParent( $parentTemplate );

    /**
     * adds parent template file.
     * 
     * @param $parentTemplate
     * @return mixed
     */
    public function addParent( $parentTemplate );

    /**
     * sets a value. 
     * 
     * @param string $name
     * @param mixed  $value
     * @return mixed
     */
    public function set( $name, $value );

    /**
     * sets array of data.
     * 
     * @param array $data
     * @return mixed
     */
    public function assign( $data );

    /**
     * gets a value, or the $default if not set. 
     * 
     * @param string       $name
     * @param null|mixed   $default
     * @return mixed
     */
    public function get( $name=null, $default=null );

    /**
     * @param string $name
     * @param array|mixed $default
     * @return mixed|null
     */
    public function arr( $name=null, $default=array() );
    
    /**
     * renders a template.
     * 
     * @return mixed
     */
    public function render();

    /**
     * renders a block: rendering without parent. 
     * 
     * @param $name
     * @param $blockName
     * @return mixed
     */
    public function block( $name, $blockName );

    /**
     * rendering output from own php file.
     */
    public function renderSelf();
}
