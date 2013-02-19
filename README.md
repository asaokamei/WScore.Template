WScore.Template
===============

A simple template engine only with PHP.

*   no parsing.
*   multiple inheritence, like Twig.
*   block, sort of there.
*   automatic output encoding and filters.


Multiple Inheritence
--------------------

layout.php:
```php
Layout:<?php echo $_v->get( 'content' ); ?>
```

and you have example.php
```php
<?php $_v->parent( __DIR__ . '/layout.php' ); ?>
test:<?php echo $_v->test;?>
```

and you run:

```php
$content = $t->render( 'sample.php', array( 'test' => 'This is a sample.' ) );
```

then you will get:

    Layout:test:This is a sample.


To-Do
-----

must think about how to manage lots of filters with ease.

