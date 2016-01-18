<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link(__('New Role'), ['action' => 'add']); ?></li>
    <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']); ?></li>
    <li><?= $this->Html->link(__('New User'), ['controller' => ' Users', 'action' => 'add']); ?></li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); ?>

<?= __('Search') ?> <input id="filter" type="text"/>
<table id="tablefilter" class="table table-striped" cellpadding="0" cellspacing="0" data-filter="#filter" data-filter-text-only="true">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id'); ?></th>

            <th><?= $this->Paginator->sort('name'); ?></th>

            <th class="actions"><?= __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $role): ?>
        <tr class="tableclick">
            <td><?= $this->Number->format($role->id) ?></td>
            <td><?= h($role->name) ?></td>
            <td class="actions">
                <?= $this->Html->link('', ['action' => 'view', $role->id], ['title' => __('View'), 'class' => 'btn btn-default glyphicon glyphicon-eye-open doaction', 'id' => 'view/' . $role->id]) ?>
                <?= $this->Html->link('', ['action' => 'edit', $role->id], ['title' => __('Edit'), 'class' => 'btn btn-default glyphicon glyphicon-pencil doaction', 'id' => 'edit/' . $role->id]) ?>
                <?= $this->Form->postLink('', ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'title' => __('Delete'), 'class' => 'btn btn-default glyphicon glyphicon-trash']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
    </ul>
    <p><?= $this->Paginator->counter() ?></p>
</div>