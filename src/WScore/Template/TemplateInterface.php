<?php
namespace WScore\Template;

interface TemplateInterface
{
    public function setTemplate( $name );
    public function set( $name, $value );
    public function setDefault( $name, $value );
    public function assign( $data );
    public function parent( $parentTemplate );
    public function get( $name, $default=null );
    public function render();
}
