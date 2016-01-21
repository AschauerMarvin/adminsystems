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
     * @return HTML to replace 1 or 0 with icons
     */
    public function boolean($bool)
    {
        if ($bool) {
            return '<span class="glyphicon glyphicon-ok icon-enabled"></span>';
        } else {
            return '<span class="glyphicon glyphicon-remove icon-disabled"></span>';
        }
    }
}
