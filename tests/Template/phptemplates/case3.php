<?php
/** @var $this \WScore\Template\TemplateInterface */
$this->setParent( __DIR__ . '/layout.php' );
$this->blockname = 'block name';
$this->block = $this->block( 'block', __DIR__ . '/block.php' );
?>
test:<?php echo $this->test;?>
