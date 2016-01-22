<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?=
    $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span> ' .
        __('Delete'),
        ['action' => 'delete', $setting->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id), 'escape' => false, 'class' => 'red']
    )
    ?>
    </li>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('List Settings'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
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