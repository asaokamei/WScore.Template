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
        require_once( __DIR__ . '/../../src/WScore/Template/DataTrait.php' );
        require_once( __DIR__ . '/../../src/WScore/Template/Filter/Filters.php' );
        require_once( __DIR__ . '/Mocks/DataMock.php' );
        $this->filters = new Filters();
        $this->data    = new DataMock();
        $this->data->filters = $this->filters;
    }
    
    function test_0()
    {
        $this->assertEquals( 'WScore\tests\Template\Mocks\DataMock', get_class( $this->data ) );
        $this->assertEquals( 'WScore\Template\Filter\Filters', get_class( $this->data->filters ) );
    }
    
    function test_set_and_get()
    {
        $this->data->set( 'test', 'test value' );
        $this->assertEquals( 'test value', $this->data->get( 'test' ) );
        $this->assertEquals( 'test value', $this->data->test );
        $this->assertEquals( 'test value', $this->data->test->h() );
        $this->assertEquals( 'test value', $this->data->test->get() );
    }
    
    function test_h_as_html()
    {
        $text = '<b>bold</b>';
        $html = htmlspecialchars( $text );
        $this->data->set( 'test', $text );
        $this->assertEquals( $text, $this->data->get( 'test' ) );
        $this->assertEquals( $html, $this->data->h( 'test' ) );
        $this->assertEquals( $text, $this->data->test );
        $this->assertEquals( $html, $this->data->test->h() );
        $this->assertEquals( $text, $this->data->test->get() );
        $this->assertEquals( $html, $this->data->test->h()->get() );
        
    }
}