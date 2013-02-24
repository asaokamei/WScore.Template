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
     * renders a template.
     * 
     * @return mixed
     */
    public function render();
}