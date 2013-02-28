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
     * sets a value. 
     * 
     * @param string $name
     * @param mixed  $value
     * @return mixed
     */
    public function set( $name, $value );

    /**
     * sets a value only if it is not set yet. 
     * 
     * @param string $name
     * @param mixed  $value
     * @return mixed
     */
    public function setDefault( $name, $value );

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
    public function get( $name, $default=null );

    /**
     * @param string $name
     * @param array|mixed $default
     * @return mixed|null
     */
    public function arr( $name, $default=array() );
    
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
