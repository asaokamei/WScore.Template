<?php
namespace WScore\Template;

require_once( __DIR__ . '/require.php' );

$view = new \WScore\Template\Template();
$view->filter =     new \WScore\Template\Filter(
    new \WScore\Template\Filter_Basic(),
    new \WScore\Template\Filter_Date()
);

return $view;

return new \WScore\Template\Template(
    new \WScore\Template\Filter(
        new \WScore\Template\Filter_Basic(),
        new \WScore\Template\Filter_Date()
    )
);