<?php
namespace WScore\tests\Template;

use \WScore\Template\Renderer;

class Renderer_Test extends \PHPUnit_Framework_TestCase
{
    /** @var \WScore\Template\Renderer */
    var $renderer;
    public function setUp()
    {
        require_once( __DIR__ . '/../../scripts/require.php' );
        $this->renderer = new Renderer();
    }

    // +----------------------------------------------------------------------+
    //  tests on using templates
    // +----------------------------------------------------------------------+
    function test_template()
    {
        $r = $this->renderer;
        $case = __DIR__ . '/renderer/file1.php';
        $content = $r->render( $case, array( 'test' => 'case1' ) );
        $this->assertEquals( 'file: case1', $content );
    }
    
    function test_self()
    {
        $case = __DIR__ . '/renderer/self1.php';
        ob_start();
        include( $case );
        $content = ob_get_clean();
        $this->assertEquals( 'file: selfTest', $content );
    }
}
