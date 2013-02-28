<?php
$this->setParent( 'layout.php' );
$this->blockname = 'block name';
$this->block( 'block', 'block.php' );
?>
test:<?php echo $this->test;?>
