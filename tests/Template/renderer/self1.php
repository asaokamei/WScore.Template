<?php
use \WScore\Template\Renderer;

/** @var $t \WScore\Template\Template */
require_once( __DIR__ . '/../../../scripts/require.php' );
$renderer = new Renderer();
$renderer->renderSelf();
$test = 'selfTest';
?>
file: <?php echo $test; ?>
