<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('Overview'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>
<?= $this->Form->create($user); ?>
<fieldset>
    <legend><?= __('Add {0}', ['User']) ?></legend>
    <?php
    echo $this->Form->input('username');
    echo $this->Form->input('firstname');
    echo $this->Form->input('lastname');
    echo $this->Form->input('mail');
    echo $this->Form->input('password');
    echo $this->Form->input('role_id', ['options' => $roles]);
    echo $this->Form->input('admin');
    echo $this->Form->input('level');
    ?>
</fieldset>
<?= $this->Form->button(__("Add")); ?>
<?= $this->Form->end() ?>