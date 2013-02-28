<?php
namespace WScore\Template;

$view = new \WScore\Template\PhpTemplate();
$view->filter =     new \WScore\Template\Filter(
    new \WScore\Template\Filter_Basic(),
    new \WScore\Template\Filter_Date()
);
return $view;

