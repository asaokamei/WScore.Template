<?php
namespace WScore\Template\Filter;

use \Michelf\MarkdownExtra as MD;

class Filters
{
    /**
     * @var null|string
     */
    public $locale = null;

    /**
     * @var \Closure[]
     */
    public $filters = array();

    /**
     * @param string $name
     * @param \Closure $filter
     */
    public function setFilter( $name, $filter ) {
        $this->filters[ $name ] = $filter;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \RuntimeException
     */
    public function __call( $method, $args )
    {
        if( !isset( $this->filters[ $method ] ) ) {
            throw new \RuntimeException( 'no such filter:' . $method );
        }
        if( !isset( $args[0] ) ) {
            throw new \RuntimeException( 'no values set for filter:' . $method );
        }
        $filter = $this->filters[ $method ];
        return $filter( $args[0] );
    }

    /**
     * @param $v
     * @return string
     */
    public function h( $v ) {
        return htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' );
    }

    /**
     * @param $v
     * @return string
     */
    public function br( $v ) {
        return nl2br( $v );
    }

    /**
     * @param $v
     * @return string
     */
    public function text( $v ) {
        return $this->br( $this->h( $v ) );
    }

    /**
     * @param $v
     * @return string
     */
    public function pre( $v ) {
        return '<pre>' . $this->h( $v ) . '</pre>';
    }

    /**
     * @param $v
     * @return mixed
     */
    public function markdown( $v ) {
        return MD::defaultTransform( $v );
    }

    /**
     * @param $v
     * @return object
     */
    public function date( $v ) 
    {
        if( is_object( $v ) && method_exists( $v, '__toString' ) ) {
            return $v;
        }
        return $v;
    }
}