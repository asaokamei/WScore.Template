<?php
namespace WScore\tests\Template;

class PhpTemplate_Test extends \PHPUnit_Framework_TestCase
{
    /** @var \WScore\Template\PhpTemplate */
    var $template;
    public function setUp()
    {
        require_once( __DIR__ . '/../../scripts/require.php' );
        $this->template = include( __DIR__ . '/../../scripts/phptemplate.php' );
    }
    public function h( $v ) {
        return htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' );
    }
    // +----------------------------------------------------------------------+
    //  tests on using templates
    // +----------------------------------------------------------------------+
    function test_template()
    {
        $t = $this->template;
        $case = __DIR__ . '/phptemplates/case1.php';
        $t->setTemplate( $case );
        $t->test = 'case1';
        $content = $t->render();
        $this->assertEquals( 'test:case1', $content );
    }
    function test_template_with_parent()
    {
        $t = $this->template;
        $case = __DIR__ . '/phptemplates/case2.php';
        $t->setTemplate( $case );
        $t->test = 'case2';
        $content = $t->render();
        $this->assertContains( 'Layout:test:case2', $content );
    }
    function test_self_template()
    {
        ob_start();
        include __DIR__ . '/phptemplates/self.php';
        $content = ob_get_clean();
        $this->assertContains( 'Layout:test:selfTest', $content );
    }
    function test_block_template()
    {
        $t = $this->template;
        $case = __DIR__ . '/phptemplates/case3.php';
        $t->setTemplate( $case );
        $t->test = 'case3';
        $content = $t->render();
        $this->assertEquals( "Layout:test:case3\nBlock: block name", $content );
    }
    // +----------------------------------------------------------------------+
    //  tests on assignments and basic filters.
    // +----------------------------------------------------------------------+
    function test_simple_assignments()
    {
        $t = $this->template;
        $t->set( 'test', 'value' );
        $this->assertEquals( 'value', $t->get( 'test' ) );
        $this->assertEquals( 'value', $t->test );
        $this->assertEquals(  null  , (string) $t->none );
    }
    function test_magic_get_returns_html_safe()
    {
        $word = "<b>bold</b>";
        $t = $this->template;
        $t->set( 'test', $word );
        $this->assertEquals( $word, $t->get( 'test' ) );
        $this->assertEquals( $this->h( $word ), $t->test );
    }
    function test_basic_filters()
    {
        $t = $this->template;
        $word = "<b>bold</b>\nnext line";
        $t->set( 'test', $word );
        $this->assertEquals( $this->h( $word ), $t->h( 'test' ) );
        $this->assertEquals( nl2br(    $word ), (string) $t->_( 'test' )->br() );
        $this->assertEquals( nl2br( $this->h( $word ) ), $t->test->br() );
        $this->assertEquals( $this->h( $word ), $t->h( 'test' ) );
        $this->assertEquals( nl2br(    $word ), $t->br( 'test' ) );
    }
    function test_arr_returns_empty_array()
    {
        $this->assertEquals( array(), $this->template->arr( 'list' ) );
    }
    function test_arrays()
    {
        $t = $this->template;
        $list = array( 'Jonathan', 'Joester' );
        $t->set( 'list', $list );
        $this->assertEquals( $list, $t->get( 'list' ) );
    }
    function test_true_on_unassigned()
    {
        $this->assertTrue( !$this->template->get( 'list' ) );
    }
    function test_external_date_filters()
    {
        $dot = function( $v ) {
            return str_replace( '-', '.', $v );
        };
        $t = $this->template;
        $t->getFilters()->setFilter( 'dot', $dot );
        $date = '1981-03-04';
        $t->set( 'date', $date );
        $this->assertEquals( $date, $t->get( 'date' ) );
        $this->assertEquals( str_replace( '-', '.', $date ), (string) $t->_( 'date' )->dot() );
        $this->assertEquals( str_replace( '-', '.', $date ), $t->dot( 'date' ) );
    }
    // +----------------------------------------------------------------------+
}
