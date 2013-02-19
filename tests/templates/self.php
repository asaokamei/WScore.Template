<?php

/** @var $t \WScore\Template\Template */
$t = include( __DIR__ . '/../../scripts/instance.php' );
$t->test = 'selfTest';
$t->parent( __DIR__ . '/layout.php' );
$t->renderSelf();
?>
test:<?php echo $t->test; ?>
<?php unset( $t ); ?>