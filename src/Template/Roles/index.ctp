<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?= $this->Html->link('<span class="glyphicon glyphicon-plus"></span> ' . __('New Role'), ['action' => 'add'], ['class' => 'green', 'escape' => false]) ?></li>

<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', $this->fetch('tb_actions')); ?>

<h1>Roles</h1><input id="filter" type="text" placeholder="<?= __('Search') ?>" />
<div class="table-responsive">
<table id="tablefilter" class="table table-striped" cellpadding="0" cellspacing="0" data-filter="#filter" data-filter-text-only="true">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('name'); ?></th>

            <th class="actions"><?= __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($roles as $role): ?>
        <tr class="tableclick">
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
</div>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
    </ul>
    <p><?= $this->Paginator->counter() ?></p>
</div>