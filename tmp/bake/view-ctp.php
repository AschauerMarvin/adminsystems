<?php

use Cake\Utility\Inflector;

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
    return $fields + $value;
}, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
    })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
?>
<CakePHPBakeOpenTagphp
$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->start('tb_actions');
CakePHPBakeCloseTag>
<li><CakePHPBakeOpenTag= $this->Html->link(__('Edit <?= $singularHumanName ?>'), ['action' => 'edit', <?= $pk ?>]) CakePHPBakeCloseTag> </li>
<li><CakePHPBakeOpenTag= $this->Form->postLink(__('Delete <?= $singularHumanName ?>'), ['action' => 'delete', <?= $pk ?>], ['confirm' => __('Are you sure you want to delete # {0}?', <?= $pk ?>)]) CakePHPBakeCloseTag> </li>
<li><CakePHPBakeOpenTag= $this->Html->link(__('List <?= $pluralHumanName ?>'), ['action' => 'index']) CakePHPBakeCloseTag> </li>
<li><CakePHPBakeOpenTag= $this->Html->link(__('New <?= $singularHumanName ?>'), ['action' => 'add']) CakePHPBakeCloseTag> </li>
<?php
$done = [];
foreach ($associations as $type => $data) {
    foreach ($data as $alias => $details) {
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {?>
<li><CakePHPBakeOpenTag= $this->Html->link(__('List <?= $this->_pluralHumanName($alias) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'index']) CakePHPBakeCloseTag> </li>
<li><CakePHPBakeOpenTag= $this->Html->link(__('New <?= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) ?>'), ['controller' => '<?= $details['controller'] ?>', 'action' => 'add']) CakePHPBakeCloseTag> </li>
<?php
            $done[] = $details['controller'];
        }
    }
}
?>
<CakePHPBakeOpenTagphp $this->end(); CakePHPBakeCloseTag>
<CakePHPBakeOpenTagphp $this->assign('tb_sidebar', '<ul class="nav nav-sidebar">' . $this->fetch('tb_actions') . '</ul>'); CakePHPBakeCloseTag>

<div class="panel panel-default">
    <!-- Panel header -->
    <div class="panel-heading">
        <h3 class="panel-title"><CakePHPBakeOpenTag= h($<?= $singularVar ?>-><?= $displayField ?>) CakePHPBakeCloseTag></h3>
    </div>
    <table class="table table-striped" cellpadding="0" cellspacing="0">
<?php if ($groupedFields['string']) : ?>
<?php foreach ($groupedFields['string'] as $field) : ?>
        <tr>
<?php
if (isset($associationFields[$field])) :
$details = $associationFields[$field];
?>
            <td><CakePHPBakeOpenTag= __('<?= Inflector::humanize($details['property']) ?>') CakePHPBakeCloseTag></td>
            <td><CakePHPBakeOpenTag= $<?= $singularVar ?>->has('<?= $details['property'] ?>') ? $this->Html->link($<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['displayField'] ?>, ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', $<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['primaryKey'][0] ?>]) : '' CakePHPBakeCloseTag></td>
<?php else : ?>
            <td><CakePHPBakeOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakeCloseTag></td>
            <td><CakePHPBakeOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
<?php endif; ?>
        </tr>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($groupedFields['number']) : ?>
<?php foreach ($groupedFields['number'] as $field) : ?>
        <tr>
            <td><CakePHPBakeOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakeCloseTag></td>
            <td><CakePHPBakeOpenTag= $this->Number->format($<?= $singularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
        </tr>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($groupedFields['date']) : ?>
<?php foreach ($groupedFields['date'] as $field) : ?>
        <tr>
            <td><?= "<?= __('" . Inflector::humanize($field) . "') ?>" ?></td>
            <td><CakePHPBakeOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
        </tr>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($groupedFields['boolean']) : ?>
<?php foreach ($groupedFields['boolean'] as $field) : ?>
        <tr>
            <td><CakePHPBakeOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakeCloseTag></td>
            <td><CakePHPBakeOpenTag= $<?= $singularVar ?>-><?= $field ?> ? __('Yes') : __('No'); CakePHPBakeCloseTag></td>
        </tr>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($groupedFields['text']) : ?>
<?php foreach ($groupedFields['text'] as $field) : ?>
        <tr>
            <td><CakePHPBakeOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakeCloseTag></td>
            <td><CakePHPBakeOpenTag= $this->Text->autoParagraph(h($<?= $singularVar ?>-><?= $field ?>)); CakePHPBakeCloseTag></td>
        </tr>
<?php endforeach; ?>
<?php endif; ?>
    </table>
</div>

<?php
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
$otherSingularVar = Inflector::variable($alias);
$otherPluralHumanName = Inflector::humanize($details['controller']);
?>
<div class="panel panel-default">
    <!-- Panel header -->
    <div class="panel-heading">
        <h3 class="panel-title"><CakePHPBakeOpenTag= __('Related <?= $otherPluralHumanName ?>') CakePHPBakeCloseTag></h3>
    </div>
    <CakePHPBakeOpenTagphp if (!empty($<?= $singularVar ?>-><?= $details['property'] ?>)): CakePHPBakeCloseTag>
        <table class="table table-striped">
            <thead>
            <tr>
<?php foreach ($details['fields'] as $field): ?>
                <th><CakePHPBakeOpenTag= __('<?= Inflector::humanize($field) ?>') CakePHPBakeCloseTag></th>
<?php endforeach; ?>
                <th class="actions"><CakePHPBakeOpenTag= __('Actions') CakePHPBakeCloseTag></th>
            </tr>
            </thead>
            <tbody>
            <CakePHPBakeOpenTagphp foreach ($<?= $singularVar ?>-><?= $details['property'] ?> as $<?= $otherSingularVar ?>): CakePHPBakeCloseTag>
                <tr>
<?php foreach ($details['fields'] as $field): ?>
                    <td><CakePHPBakeOpenTag= h($<?= $otherSingularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
<?php endforeach; ?>
<?php $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; ?>
                    <td class="actions">
                        <CakePHPBakeOpenTag= $this->Html->link('', ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', <?= $otherPk ?>], ['title' => __('View'), 'class' => 'btn btn-default glyphicon glyphicon-eye-open']) CakePHPBakeCloseTag>
                        <CakePHPBakeOpenTag= $this->Html->link('', ['controller' => '<?= $details['controller'] ?>', 'action' => 'edit', <?= $otherPk ?>], ['title' => __('Edit'), 'class' => 'btn btn-default glyphicon glyphicon-pencil']) CakePHPBakeCloseTag>
                        <CakePHPBakeOpenTag= $this->Form->postLink('', ['controller' => '<?= $details['controller'] ?>', 'action' => 'delete', <?= $otherPk ?>], ['confirm' => __('Are you sure you want to delete # {0}?', <?= $otherPk ?>), 'title' => __('Delete'), 'class' => 'btn btn-default glyphicon glyphicon-trash']) CakePHPBakeCloseTag>
                    </td>
                </tr>
            <CakePHPBakeOpenTagphp endforeach; CakePHPBakeCloseTag>
            </tbody>
        </table>
    <CakePHPBakeOpenTagphp else: CakePHPBakeCloseTag>
        <p class="panel-body">no related <?= $otherPluralHumanName ?></p>
    <CakePHPBakeOpenTagphp endif; CakePHPBakeCloseTag>
</div>
<?php endforeach; ?>