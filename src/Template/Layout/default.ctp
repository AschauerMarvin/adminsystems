<?php

use Cake\Core\Configure;


/**
 * Default `body` block.
 */
$this->prepend('tb_body_attrs', ' class="' . implode(' ', array($this->request->controller, $this->request->action)) . '" ');
if (!$this->fetch('tb_body_start')) {
    $this->start('tb_body_start');
    echo '<body' . $this->fetch('tb_body_attrs') . '>';
    $this->end();
}
/**
 * Default `flash` block.
 */
if (!$this->fetch('tb_flash')) {
    $this->start('tb_flash');
    if (isset($this->Flash))
        echo $this->Flash->render();
    $this->end();
}
if (!$this->fetch('tb_body_end')) {
    $this->start('tb_body_end');
    echo '</body>';
    $this->end();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?= $this->fetch('title') . ' | ' . Configure::read('Branding.longname') ?>
    </title>
    <!--[if IE]><link rel="shortcut icon" href="img/favicon.ico"><![endif]-->
    <link rel="icon" href="img/favicon.png">

    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->css('bootstrap-theme.min') ?>
    <?= $this->Html->css('custom') ?>

    <?php 
    $this->prepend('script', $this->Html->script(['jquery.min', 'bootstrap.min', 'jquery.footable', 'jquery.footable-filter', 'u/tablefilter', 'u/tableclick']));
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>

    <?php
    echo $this->fetch('tb_body_start');
    echo $this->fetch('tb_flash');
    echo $this->fetch('content');
    echo $this->fetch('script');
    echo $this->fetch('tb_body_end');
    ?>
</html>
