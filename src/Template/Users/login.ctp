<?php
use Cake\Core\Configure;
$this->extend('../Layout/TwitterBootstrap/signin');
?>


<div class="container">

<div class="form-signin">
<?= $this->Html->image(Configure::read('Branding.logo')); ?>
</div>

<?php 
$this->Form->templates([
    'inputContainer' => '{{content}}'
]);
?>
<?=$this->Form->create('Users', ['class' => 'form-signin'])?>
<h2 class="form-signin-heading"><?= __('Please sign in') ?></h2>
<?=$this->Form->input('username', ['class' => 'form-control', 'id' => 'inputUsername', 'placeholder' => __('Username'), 'autofocus', 'label' => false])?>
<?=$this->Form->input('password', ['class' => 'form-control', 'id' => 'inputPassword', 'placeholder' => __('Password'), 'label' => false, 'value' => ''])?>
<?=$this->Form->button(__('Sign in'), ['class' => 'btn btn-lg btn-primary btn-block']);?>
<?=$this->Form->end()?>

</div>