<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('Overview'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>
<?= $this->Form->create($role); ?>
<fieldset>
    <legend><?= __('Add {0}', ['Role']) ?></legend>
    <?php
    echo $this->Form->button(__("Add"));
    echo $this->Form->input('name');
    $i = 0;
    foreach(Configure::read('Permissions') as $permission=>$actions){
        ?>
<div class="panel panel-default">
  <div data-toggle="collapse" data-target="#toggle<?= $i ?>" class="panel-heading">
    <h3 class="panel-title"><?= $permission ?>  </h3>
  </div>
  <div id="toggle<?= $i ?>" class="panel-body panel-collapse collapse in">
  <?php 
        foreach($actions as $action){
            echo $this->Form->input('permission.' . $permission . '.' . $action, ['type' => 'checkbox', 'label' => __($action)]);
        }
  ?>
  </div>
</div>
        <?php
        $i++;
    }
    ?>
</fieldset>
<?= $this->Form->button(__("Add")); ?>
<?= $this->Form->end() ?>
<br /><br />