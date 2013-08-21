<?php
namespace WScore\Template;

trait DataTrait
{
    /**
     * @var string
     */
    public $_value = null;
    
    /**
     * @var array
     */
    public $data = array();

    /**
     * @Inject
     * @var \WScore\Template\Filter\Filters
     */
    public $filters;

    /**
     * @return Filter\Filters
     */
    public function getFilters() {
        return $this->filters;
    }
    // +----------------------------------------------------------------------+
    //  setting values.
    // +----------------------------------------------------------------------+
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
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function set( $name, $value ) {
        $this->data[ $name ] = $value;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set( $name, $value ) {
        $this->set( $name, $value );
    }

    // +----------------------------------------------------------------------+
    //  getting values.
    // +----------------------------------------------------------------------+
    /**
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function get( $name=null, $default=null ) 
    {
        if( !$name ) return $this->_value;
        $this->_value = array_key_exists( $name, $this->data ) ? $this->data[ $name ] : $default;
        return $this->_value;
    }

    /**
     * @param string $name
     * @param array|mixed $default
     * @return mixed|null
     */
    public function arr( $name=null, $default=array() ) {
        return $this->get( $name, $default );
    }

    /**
     * @param $name
     * @return $this
     */
    public function __get( $name ) {
        return $this->_( $name );
    }

    /**
     * @param $name
     * @return $this
     */
    public function _( $name ) {
        $this->_value = $this->get( $name );
        return $this;
    }
    
    /**
     * @param $method
     * @param $args
     * @return $this
     */
    public function __call( $method, $args ) 
    {
        if( is_callable( [ $this->filters, $method ] ) ) {
            $v = isset( $args[0] ) ? $this->get( $args[0] ) : $this->_value;
            if( is_object( $v ) && method_exists( $v, '__toString' ) ) {
                $v = (string) $v;
            }
            $this->_value = $this->filters->$method( $v );
            return $this;
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function __toString() {
        return (string) $this->_value;
    }
    // +----------------------------------------------------------------------+
}