<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
?>
    <li><?=$this->Html->link(__('New Setting'), ['action' => 'add']);?></li>
<?php $this->end();?>
<?php $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>');?>

        <?= __('Search') ?> <input id="filter" type="text"/>

<table id="tablefilter" class="table table-striped" cellpadding="0" cellspacing="0" data-filter="#filter" data-filter-text-only="true">
    <thead>
        <tr>
            <th><?=$this->Paginator->sort('id');?></th>
            <th><?=$this->Paginator->sort('name');?></th>
            <th><?=$this->Paginator->sort('value');?></th>
            <th class="actions"><?=__('Actions');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($settings as $setting): ?>
        <tr>
            <td><?=$this->Number->format($setting->id)?></td>
            <td><?=h($setting->name)?></td>
            <td><?=h($setting->value)?></td>
            <td class="actions">
                <?=$this->Html->link('', ['action' => 'view', $setting->id], ['title' => __('View'), 'class' => 'btn btn-default glyphicon glyphicon-eye-open'])?>
                <?=$this->Html->link('', ['action' => 'edit', $setting->id], ['title' => __('Edit'), 'class' => 'btn btn-default glyphicon glyphicon-pencil'])?>
                <?=$this->Form->postLink('', ['action' => 'delete', $setting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id), 'title' => __('Delete'), 'class' => 'btn btn-default glyphicon glyphicon-trash'])?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="paginator">
    <ul class="pagination">
        <?=$this->Paginator->prev('< ' . __('previous'))?>
        <?=$this->Paginator->numbers(['before' => '', 'after' => ''])?>
        <?=$this->Paginator->next(__('next') . ' >')?>
    </ul>
    <p><?=$this->Paginator->counter()?></p>
</div>

<?php
echo CONFIG;
pr(Configure::read());
?>