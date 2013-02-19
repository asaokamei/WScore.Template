<?php
namespace WScore\Template;

class Filter_Date
{
    public function dot( $v ) {
        return str_replace( '-', '.', $v );
    }

}