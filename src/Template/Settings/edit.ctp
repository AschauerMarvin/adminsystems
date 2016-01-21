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
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>
<?= $this->Form->create($setting); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['Setting']) ?></legend>
    <?php
    $type = $setting->type;

    if($type == 'Boolean'){
        $form = 'checkbox';
        $label = __('Enabled');
    }else{
        $form = 'text';
        $label = __('Setting');
    }

    echo $this->Form->input('name', ['type' => 'hidden']);
    echo $this->Form->input('name', ['disabled']);
    echo $this->Form->input('value', ['type' => $form, 'label' => $label]);
    echo $this->Form->input('type', ['type' => 'hidden']);
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>