<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
<li><?= $this->Html->link('<span class="glyphicon glyphicon-pencil"></span> ' . __('Edit'), ['action' => 'edit', $role->id], ['class' => 'green', 'escape' => false]) ?> </li>
<li><?= $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span> ' . __('Delete'), ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'escape' => false, 'class' => 'red']) ?> </li>
<li><?= $this->Html->link('<span class="glyphicon glyphicon-list"></span> ' . __('Overview'), ['action' => 'index'], ['class' => '', 'escape' => false]) ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>

<?= '<h1>' . __('View Role') . '</h1>' ?>
<div class="panel panel-default">
    <div data-toggle="collapse" data-target="#toggle1" class="panel-heading">
        <h3 class="panel-title"><?= h($role->name) ?></h3>
    </div>
  <div id="toggle1" class="panel-collapse collapse in">
    <table class="table table-striped" cellpadding="0" cellspacing="0">
        <tr>
            <td><?= __('Name') ?></td>
            <td><?= h($role->name) ?></td>
        </tr>
    </table>
    </div>
</div>

<div class="panel panel-default">
  <div data-toggle="collapse" data-target="#toggle3" class="panel-heading">
    <h3 class="panel-title"><?= __('Permissions') ?></h3>
  </div>
  <div id="toggle3" class="panel-body panel-collapse collapse in">
<?php 
    if(!empty($role->permissions)){
        $role->permissions = json_decode($role->permissions);
    }
    $i = 4;
    foreach(Configure::read('Permissions') as $permission=>$actions){
        ?>
<div class="panel panel-default">
  <div data-toggle="collapse" data-target="#toggle<?= $i ?>" class="panel-heading">
    <h3 class="panel-title"><?= $permission ?>  </h3>
  </div>
  <div id="toggle<?= $i ?>" class="panel-body panel-collapse collapse in">
  <?php 
        foreach($actions as $action){
            $perm = (!empty($role->permissions->$permission->$action))?$role->permissions->$permission->$action:0;
            $bool = $this->Common->boolean($perm, false);
            echo $bool['Html'] . ' <span class="'.$bool['Class'].'">'.$action.'</span>';
            echo '<br />';
        }
  ?>
  </div>
</div>
        <?php
        $i++;
    }
    ?>
    </div>
</div>


<div class="panel panel-default">
    <!-- Panel header -->
    <div data-toggle="collapse" data-target="#toggle2" class="panel-heading">
        <h3 class="panel-title"><?= __('Related Users') ?></h3>
    </div>
    <?php if (!empty($role->users)): ?>
  <div id="toggle2" class="panel-collapse collapse in">
  <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Username') ?></th>
                <th><?= __('Firstname') ?></th>
                <th><?= __('Lastname') ?></th>
                <th><?= __('Mail') ?></th>
                <th><?= __('Password') ?></th>
                <th><?= __('Role Id') ?></th>
                <th><?= __('Admin') ?></th>
                <th><?= __('Level') ?></th>
                <th><?= __('Domains') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($role->users as $users): ?>
                <tr class="tableclick">
                    <td><?= h($users->id) ?></td>
                    <td><?= h($users->username) ?></td>
                    <td><?= h($users->firstname) ?></td>
                    <td><?= h($users->lastname) ?></td>
                    <td><?= h($users->mail) ?></td>
                    <td><?= h($users->password) ?></td>
                    <td><?= h($users->role_id) ?></td>
                    <td><?= h($users->admin) ?></td>
                    <td><?= h($users->level) ?></td>
                    <td><?= h($users->domains) ?></td>
                    <td><?= h($users->created) ?></td>
                    <td><?= h($users->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link('', ['controller' => 'Users', 'action' => 'view', $users->id], ['title' => __('View'), 'class' => 'btn btn-default glyphicon glyphicon-eye-open doaction']) ?>
                        <?= $this->Html->link('', ['controller' => 'Users', 'action' => 'edit', $users->id], ['title' => __('Edit'), 'class' => 'btn btn-default glyphicon glyphicon-pencil doaction']) ?>
                        <?= $this->Form->postLink('', ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id), 'title' => __('Delete'), 'class' => 'btn btn-default glyphicon glyphicon-trash']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        </div>
    <?php else: ?>
        <div id="toggle2" class="panel-collapse collapse out">
        <p class="panel-body"><?= __('No related Users') ?></p>
        </div>
    <?php endif; ?>
</div>
