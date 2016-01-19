<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class DynamicConfigComponent extends Component
{

    /*
     * Get the settings type based on its default content
     * If there is any ',' in the default, the setting should be an array
     * If the default value is 1 or 0, assume boolean
     * On any other cases use as string
     */
    public function getType($setting)
    {
        if (strpos($setting, ',') !== false) {
            return 'Array';
        } elseif ($setting == 1 || $setting == 0) {
            return 'Boolean';
        } else {
            return 'Text';
        }
    }
}
