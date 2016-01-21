<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('List Settings'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>

<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>

<?= $this->Form->create($setting); ?>
<fieldset>
    <legend><?= __('Add {0}', ['Setting']) ?></legend>
    <?php
    $type = $availableSettings[$name]['Type'];
    if($type == 'Boolean'){
        $form = 'checkbox';
        $label = __('Enabled');
    }else{
        $form = 'text';
        $label = __('Setting');
    }

    echo $this->Form->input('name', ['value' => $name, 'type' => 'hidden']);
    echo $this->Form->input('name', ['value' => $name, 'disabled']);
    echo $this->Form->input('value', ['type' => $form, 'label' => $label, 'default' => $availableSettings[$name]['Value']]);
    echo $this->Form->input('type', ['type' => 'hidden','value' => $type]);
    ?>
</fieldset>
<?= $this->Form->button(__("Add")); ?>
<?= $this->Form->end() ?>