<?php
namespace WScore\tests\Template;

use WScore\Template\DataTrait;
use WScore\Template\Filter\Filters;
use WScore\tests\Template\Mocks\DataMock;

class DataTrait_Test extends \PHPUnit_Framework_TestCase
{
    /** @var DataMock */
    var $data;
    /** @var  Filters */
    var $filters;
    
    public function setUp()
    {
        require_once( __DIR__ . '/Mocks/DataMock.php' );
        require_once( __DIR__ . '/../../src/WScore/Template/DataTrait.php' );
        require_once( __DIR__ . '/../../src/WScore/Template/Filter/Filters.php' );
        $this->filters = new Filters();
        $this->data    = new DataMock();
        $this->data->filters = $this->filters;
    }
    
    function test_0()
    {
        $this->assertEquals( 'test', get_class( $this->data ) );
        $this->assertEquals( 'test', get_class( $this->data->filters ) );
    }
}