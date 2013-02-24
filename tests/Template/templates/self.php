<?php

/** @var $t \WScore\Template\Template */
$t = include( __DIR__ . '/../../../scripts/instance.php' );
// need to clone; $view is left in the instance.php code,
// hence will not destroyed by unset, and test fails...
$t = clone( $t );
$t->test = 'selfTest';
$t->parent( __DIR__ . '/layout.php' );
$t->renderSelf();
?>
test:<?php echo $t->test; ?>
<?php unset( $t ); ?>