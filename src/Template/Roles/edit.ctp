<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?=
    $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $role->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $role->id)]
    )
    ?>
    </li>
    <li><?= $this->Html->link(__('List Roles'), ['action' => 'index']) ?></li>
    <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
    <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); ?>
<?= $this->Form->create($role); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['Role']) ?></legend>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('permissions');
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>