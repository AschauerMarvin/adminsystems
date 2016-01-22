<?php

namespace App\View\Helper;

use Cake\View\Helper;

class CommonHelper extends Helper
{

    /**
     * boolean method
     *
     * Replaces boolean values (1 or 0) with bootstrap icons
     *
     * @param boolean|$bool
     * @param boolean|$returnArray Defines the return value, true for html (default) or false for an array
     * @return HTML to replace 1 or 0 with icons
     * @return array with html, type and class
     */
    public function boolean($bool, $returnArray = true)
    {
        if ($bool) {
            if ($returnArray) {
                return '<span class="glyphicon glyphicon-ok icon-enabled"></span>';
            } else {
                return ['Html' => '<span class="glyphicon glyphicon-ok icon-enabled"></span>', 'Type' => true, 'Class' => 'green'];
            }
        } else {
            if ($returnArray) {
                return '<span class="glyphicon glyphicon-remove icon-disabled"></span>';
            } else {
                return ['Html' => '<span class="glyphicon glyphicon-remove icon-disabled"></span>', 'Type' => false, 'Class' => 'red'];
            }
        }
    }
}
