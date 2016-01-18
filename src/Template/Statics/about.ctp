<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
?>

<h1><?= __('About') ?></h1>

<?= $this->Html->image(Configure::read('Branding.logo')) ?>
<br /><br />
<?= Configure::read('Branding.longname') ?>
<br />
<?= __('Version') . ' ' . Configure::read('Version.long') ?>
