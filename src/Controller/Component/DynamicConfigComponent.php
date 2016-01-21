<?php
namespace App\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class DynamicConfigComponent extends Component
{

    /**
     * loadSettings function
     *
     * Loads the available settings from the database,
     * caches the result and updates the configuration
     *
     * @return void
     */

    public function loadSettings()
    {

        $settings = Cache::read('settings', 'long');

        if ($settings === false) {

            $settingsTable = TableRegistry::get('Settings');
            $settings = $settingsTable->find('list', ['keyField' => 'name', 'valueField' => 'value']);
            $settings = $settings->toArray();
            Cache::write('settings', $settings, 'long');
        }

        foreach ($settings as $key => $setting) {
            $type = $this->getType($setting);
            if ($type == 'Text' || $type == 'Boolean') {
                Configure::write($key, $setting);
            } elseif ($type == 'Array') {
                Configure::write($key, explode(',', $setting));
            }

        }
    }

    /**
     * getType function
     *
     * Get the settings type based on its default content
     * If there is any ',' in the default, the setting should be an array
     * If the default value is 1 or 0, assume boolean
     * On any other cases use as string
     *
     * @param string $setting Setting name.
     * @return String of the Settings type
     */

    public function getType($setting)
    {
        if (empty($setting)) {
            $setting = '';
        }

        if (is_array($setting) || strpos($setting, ',') !== false) {
            return 'Array';
        } elseif ($setting === 1 || $setting === 0) {
            return 'Boolean';
        } else {
            return 'Text';
        }
    }

    /**
     * getAvailableSettings function
     *
     * This function is to load all available settings into an array
     *
     * @return Array of all available settings
     */

    public function getAvailableSettings()
    {
        foreach (Configure::read('Settings.AvailableSettings') as $setting) {
            foreach (Configure::read($setting) as $changeable => $value) {
                $availableSettings[$setting . '.' . $changeable] = ['Type' => $this->getType($value), 'Value' => $value];
            }
        }
        return $availableSettings;
    }
}
