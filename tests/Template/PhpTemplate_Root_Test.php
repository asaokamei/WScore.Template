<?php
namespace WScore\tests\Template;

class PhpTemplate_Root_Test extends \PHPUnit_Framework_TestCase
{
    /** @var \WScore\Template\TemplateInterface */
    var $template;
    public function setUp()
    {
        require_once( __DIR__ . '/../../scripts/require.php' );
        $this->template = include( __DIR__ . '/../../scripts/phptemplate.php' );
        $this->template->setRoot( __DIR__ . '/withRoot/' );
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
        $t->setTemplate( 'case1.php' );
        $t->test = 'case1';
        $content = $t->render();
        $this->assertEquals( 'test:case1', $content );
    }
    function test_template_no_trailing_slash()
    {
        $t = $this->template;
        $t->setTemplate( 'case1.php' );
        $t->test = 'case1';
        $content = $t->render();
        $this->assertEquals( 'test:case1', $content );
    }
    function test_template_with_parent()
    {
        $t = $this->template;
        $t->setTemplate( 'case2.php' );
        $t->test = 'case2';
        $content = $t->render();
        $this->assertEquals( 'Layout:test:case2', $content );
    }
    function test_template_with_parent_in_sub_folder()
    {
        $t = $this->template;
        $t->setTemplate( 'sub/case3.php' );
        $t->test = 'case3';
        $content = $t->render();
        $this->assertEquals( 'Layout:test:case3', $content );
    }
    function test_template_with_sub_parent_in_sub_folder()
    {
        $t = $this->template;
        $t->setTemplate( 'sub/case4.php' );
        $t->test = 'case4';
        $content = $t->render();
        $this->assertEquals( 'Layout:sub:test:case4', $content );
    }
    function test_template_with_block()
    {
        $t = $this->template;
        $t->setTemplate( 'sub/case5.php' );
        $t->test = 'case5';
        $content = $t->render();
        $this->assertEquals( "Layout:test:case5\nBlock: block name", $content );
    }
}