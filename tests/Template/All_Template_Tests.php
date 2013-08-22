<?php
namespace WScore\tests\Html;

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

class All_Template_Tests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite( 'all tests for WScore\'s Template' );
        $suite->addTestFile( __DIR__ . '/PhpTemplate_Test.php' );
        $suite->addTestFile( __DIR__ . '/PhpTemplate_Root_Test.php' );
        $suite->addTestFile( __DIR__ . '/DataTrait_Test.php' );

        return $suite;
    }
}