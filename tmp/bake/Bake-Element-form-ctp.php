<?php

use Cake\Utility\Inflector;

$fields = collection($fields)
        ->filter(function($field) use ($schema) {
    return $schema->columnType($field) !== 'binary';
});
?>
<CakePHPBakeOpenTagphp
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
CakePHPBakeCloseTag>
<?php if (strpos($action, 'add') === false): ?>
    <li><CakePHPBakeOpenTag=
    $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $<?= $singularVar
        ?>-><?= $primaryKey[0] ?>],
        ['confirm' => __('Are you sure you want to delete # {0}?', $<?= $singularVar ?>-><?= $primaryKey[0] ?>)]
    )
    CakePHPBakeCloseTag>
    </li>
<?php endif; ?>
    <li><CakePHPBakeOpenTag= $this->Html->link(__('List <?= $pluralHumanName ?>'), ['action' => 'index']) CakePHPBakeCloseTag></li>
<?php
$done = [];
foreach ($associations as $type => $data) {
    foreach ($data as $alias => $details) {
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
            ?>
    <li><CakePHPBakeOpenTag= $this->Html->link(__('List <?= $this->_pluralHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'index']) ?> </li>
    <li><CakePHPBakeOpenTag= $this->Html->link(__('New <?= $this->_singularHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'add']) ?> </li>
<?php
            $done[] = $details['controller'];
        }
    }
}
?>
<CakePHPBakeOpenTagphp $this->end(); CakePHPBakeCloseTag>
<CakePHPBakeOpenTagphp $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); CakePHPBakeCloseTag>
<CakePHPBakeOpenTag= $this->Form->create($<?= $singularVar ?>); CakePHPBakeCloseTag>
<fieldset>
    <legend><CakePHPBakeOpenTag= __('<?= Inflector::humanize($action) ?> {0}', ['<?= $singularHumanName ?>']) CakePHPBakeCloseTag></legend>
    <CakePHPBakeOpenTagphp
<?php
    foreach ($fields as $field) {
        if (in_array($field, $primaryKey)) {
            continue;
        }
        if (isset($keyFields[$field])) {
            ?>
    echo $this->Form->input('<?= $field ?>', ['options' => $<?= $keyFields[$field] ?>]);
<?php
            continue;
        }
        if (!in_array($field, ['created', 'modified', 'updated'])) {
            ?>
    echo $this->Form->input('<?= $field ?>');
<?php
        }
    }
    if (!empty($associations['BelongsToMany'])) {
        foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
            ?>
    echo $this->Form->input('<?= $assocData['property'] ?>._ids', ['options' => $<?= $assocData['variable'] ?>]);
<?php
        }
    }
    ?>
    CakePHPBakeCloseTag>
</fieldset>
<?php
if (strpos($action, 'add') === false)
    $submitButtonTitle = '__("Save")';
else
    $submitButtonTitle = '__("Add")';
?>
<CakePHPBakeOpenTag= $this->Form->button(<?php echo $submitButtonTitle;?>); CakePHPBakeCloseTag>
<CakePHPBakeOpenTag= $this->Form->end() CakePHPBakeCloseTag>