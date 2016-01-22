<?php
use Cake\Core\Configure;

$this->Html->css('BootstrapUI.dashboard', ['block' => true]);
$this->prepend('tb_body_attrs', ' class="' . implode(' ', array($this->request->controller, $this->request->action)) . '" ');
$this->start('tb_body_start');
?>
<body <?= $this->fetch('tb_body_attrs') ?>>

<?= $this->element('navigation') ?>


<div id="wrapper">
    <div id="sidebar-wrapper" class="sidebar">
<ul class="nav nav-pills nav-stacked">
        <?= $this->fetch('tb_actions') ?>
</ul>

    </div>
    <div id="page-content-wrapper">
        <div class="page-content">
                    <div class="col-md-12">
                    <?php 
                    if (!$this->fetch('tb_flash')) {
                        $this->start('tb_flash');
                        if (isset($this->Flash))
                            echo $this->Flash->render();
                            $this->end();
                        }
                        $this->end();
                    ?>
                        
                    <?= $this->fetch('content'); ?>
                    </div>
        </div>
    </div>
</div>


<?php

$this->start('tb_body_end');
echo '</body>';
$this->end();

/**
 * Default `flash` block.
 */
/*if (!$this->fetch('tb_flash')) {
    $this->start('tb_flash');
    if (isset($this->Flash))
        echo $this->Flash->render();
    $this->end();
}
$this->end();

$this->start('tb_body_end');
echo '</body>';
$this->end();

$this->append('content', '</div></div></div>');
echo $this->fetch('content');
*/