<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?=
    $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $setting->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]
    )
    ?>
    </li>
    <li><?= $this->Html->link(__('List Settings'), ['action' => 'index']) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); ?>
<?= $this->Form->create($setting); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['Setting']) ?></legend>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('value');
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>