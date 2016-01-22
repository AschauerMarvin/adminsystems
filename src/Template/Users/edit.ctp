<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?=
    $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span> ' .
        __('Delete'),
        ['action' => 'delete', $user->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'escape' => false, 'class' => 'red']
    )
    ?>
    </li>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('Overview'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>
<?= $this->Form->create($user); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['User']) ?></legend>
    <?php
    echo $this->Form->input('username');
    echo $this->Form->input('firstname');
    echo $this->Form->input('lastname');
    echo $this->Form->input('mail');
    echo $this->Form->input('password', ['value' => '']);
    echo $this->Form->input('role_id', ['options' => $roles]);
    echo $this->Form->input('admin');
    echo $this->Form->input('level');
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>