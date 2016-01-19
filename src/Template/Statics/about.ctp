<?php
use Cake\Core\Configure;

$this->extend('../Layout/TwitterBootstrap/dashboard');
$this->assign('title', __('About'));
?>

<h2><?= __('About') ?></h2>


<div class="panel panel-default">
  <div data-toggle="collapse" data-target="#toggle1" class="panel-heading">
    <h3 class="panel-title">
          <?= Configure::read('Branding.longname') ?>  
    </h3>
  </div>
  <div id="toggle1" class="panel-body panel-collapse collapse in">
    <?= $this->Html->image(Configure::read('Branding.logo')) ?>
    <br /><br />
    <p>
    <strong><?= $this->Html->link(Configure::read('Branding.longname'), Configure::read('Branding.url'), ['target' => '_blank']) ?></strong>
    <br />
    <?= __('Version') . ' ' . Configure::read('Version.long') ?>
    </p>
    <p><?= Configure::read('Branding.slogan') ?></p>
    <?php if(!empty(Configure::read('Branding.version'))) echo  __('Product Version') . ': ' . Configure::read('Branding.version'); ?>
    <p>
    <br />
    <?php if(Configure::read('Branding.licence')){
        if(is_readable(ROOT . DS . 'LICENCE.txt')){
            echo str_replace("\n", "<br />", file_get_contents(ROOT . DS . 'LICENCE.txt'));
        }
    }
    ?>
    </p>
  </div>
</div>

<?php 
if(Configure::read('Branding.usedSoftware')):
?>

<div class="panel panel-default">
  <div data-toggle="collapse" data-target="#toggle2" class="panel-heading">
    <h3 class="panel-title"><?= __('Used Software') ?></h3>
  </div>
  <div id="toggle2" class="panel-body panel-collapse collapse in">
    <strong>CakePHP</strong> <?= Configure::version(); ?> <br />
    <strong>jQuery</strong> <?= Configure::read('Version.jquery'); ?> <br />
    <strong>Bootstrap</strong> <?= Configure::read('Version.bootstrap'); ?> <br />
    <strong>FooTable</strong> <?= Configure::read('Version.footable'); ?> <br />
  </div>
</div>
<?php endif; ?>
