<?php

/** @var $t \WScore\Template\Template */
$a = include( __DIR__ . '/../../../scripts/phptemplate.php' );
// need to clone; $view is left in the instance.php code,
// hence will not destroyed by unset, and test fails...
$t = clone( $a );
$t->test = 'selfTest';
$t->setParent( __DIR__ . '/layout.php' );
$t->renderSelf();
?>
test:<?php echo $t->test; ?>
<?php unset( $t ); ?>