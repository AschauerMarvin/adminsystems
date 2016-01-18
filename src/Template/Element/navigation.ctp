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
          <ul class="nav navbar-nav">
<li>
<?php
if (true) {
    echo $this->Html->link('Menus', ['controller' => 'Articles', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Articles
if (true) {
    echo $this->Html->link('Articles', ['controller' => 'Menus', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Pages
if (true) {
    echo $this->Html->link('Pages', ['controller' => 'Pages', 'action' => 'index', '_full' => true]);
}
?>
</li>
<li>
<?php
// Elements
if (true) {
    echo $this->Html->link('Elements', ['controller' => 'Elements', 'action' => 'index', '_full' => true]);
}
?>
</li>

<?php
// settings dropdown
if(true):
?>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= __('Settings') ?> <span class="caret"></span></a>
<ul class="dropdown-menu">
<?php
if (true) {
    echo '<li>' . $this->Html->link('User Settings', ['controller' => 'User', 'action' => 'settings', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Settings', ['controller' => 'Settings', 'action' => 'index', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Users', ['controller' => 'Users', 'action' => 'index', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Roles', ['controller' => 'Roles', 'action' => 'index', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Domains', ['controller' => 'Domains', 'action' => 'index', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('Languages', ['controller' => 'Languages', 'action' => 'index', '_full' => true]) . '</li>';
    echo '<li>' . $this->Html->link('About', ['controller' => 'Static', 'action' => 'about', '_full' => true]) . '</li>';

}
?>
</ul>
</li>
<?php 
endif;
?>
<?php 
    echo '<li>' . $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout', '_full' => true]) . '</li>';

?>


          </ul>
        </div>
      </div>
    </nav>
