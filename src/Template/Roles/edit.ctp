<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-eye-open"></span> ' . __('View'), ['action' => 'view', $role->id], ['class' => 'green', 'escape' => false]) ?> </li>
    <li><?=
    $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span> ' .
        __('Delete'),
        ['action' => 'delete', $role->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'escape' => false, 'class' => 'red']
    )
    ?>
    </li>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('Overview'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); ?>
<?= $this->Form->create($role); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['Role']) ?></legend>
    <?php
    echo $this->Form->button(__("Save"));
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
            // get the default value from the json object
            // use empty if nothing is found
            $default = (!empty($role->permissions->$permission->$action))?$role->permissions->$permission->$action:'';
  

            echo $this->Form->input('permission.' . $permission . '.' . $action, ['type' => 'checkbox', 'label' => __($action), 'default' => $default]);
        }
  ?>
  </div>
</div>
        <?php
        $i++;
    }
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>
<br /><br />