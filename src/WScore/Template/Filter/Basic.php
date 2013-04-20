<?php
namespace WScore\Template;

use \Michelf\MarkdownExtra as MD;

class Filter_Basic
{
    public function h( $v ) {
        return htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' );
    }

    public function nl2br( $v ) {
        return nl2br( $v );
    }
    
    public function pre( $v )
    {
        return '<pre>' . $this->h( $v ) . '</pre>';
    }
    
    public function md( $v )
    {
        return MD::defaultTransform( $v );
    }
}