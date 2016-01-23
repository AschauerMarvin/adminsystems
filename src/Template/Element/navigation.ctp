<?php
use Cake\Core\Configure;
?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="navbar-brand" href="#"><?=Configure::read('Branding.name')?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
<ul class="nav navbar-nav navbar-right visible-xs">
                    <?= $this->fetch('tb_actions') ?>
</ul>
          <ul class="nav navbar-nav">
<li>
<?php
if ($this->Acl->Menus('index')) {
    echo $this->Html->link('Menus', ['controller' => 'Articles', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Articles
if ($this->Acl->Articles('index')) {
    echo $this->Html->link('Articles', ['controller' => 'Menus', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Pages
if ($this->Acl->Pages('index')) {
    echo $this->Html->link('Pages', ['controller' => 'Pages', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Elements
if ($this->Acl->Elements('index')) {
    echo $this->Html->link('Elements', ['controller' => 'Elements', 'action' => 'index', '_full' => true]);
}
?>
</li>

<?php
// settings dropdown
if($this->Acl->Settings() || $this->Acl->Users() || $this->Acl->Roles() || $this->Acl->Domains() || $this->Acl->Languages()):
?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= __('Settings') ?> <span class="caret"></span></a>
<ul class="dropdown-menu">
<?php
    if($this->Acl->Settings()) echo '<li>' . $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index', '_full' => true]) . '</li>';
    if($this->Acl->Users()) echo '<li>' . $this->Html->link('Users', ['controller' => 'Users', 'action' => 'index', '_full' => true]) . '</li>';
    if($this->Acl->Roles()) echo '<li>' . $this->Html->link('Roles', ['controller' => 'Roles', 'action' => 'index', '_full' => true]) . '</li>';
    if($this->Acl->Domains()) echo '<li>' . $this->Html->link('Domains', ['controller' => 'Domains', 'action' => 'index', '_full' => true]) . '</li>';
    if($this->Acl->Languages()) echo '<li>' . $this->Html->link('Languages', ['controller' => 'Languages', 'action' => 'index', '_full' => true]) . '</li>';
?>
</ul>
</li>
<?php 
endif;
?>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= __('System') ?> <span class="caret"></span></a>
<ul class="dropdown-menu">
<?php
if (true) {
    echo '<li>' . $this->Html->link('User Settings', ['controller' => 'User', 'action' => 'settings', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('About', ['controller' => 'Static', 'action' => 'about', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout', '_full' => true]) . '</li>';

}
?>
</ul>
</li>

          </ul>

<?php if(true): ?>
<ul class="nav navbar-nav navbar-right">
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= __('Systeme') ?> <span class="caret"></span></a>
<ul class="dropdown-menu">
<?php
    if(true) echo '<li>' . $this->Html->link('System 1', ['controller' => 'Domains', 'action' => 'switch/system1', '_full' => true]) . '</li>';
    if(true) echo '<li>' . $this->Html->link('System 2', ['controller' => 'Domains', 'action' => 'switch/system1', '_full' => true]) . '</li>';
    if(true) echo '<li>' . $this->Html->link('System 3', ['controller' => 'Domains', 'action' => 'switch/system1', '_full' => true]) . '</li>';
?>
</ul>
<?php endif; ?>
</li>

          </ul>
        </div>
      </div>
    </nav>
