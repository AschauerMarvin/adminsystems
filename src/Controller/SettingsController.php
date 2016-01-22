<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 */
class SettingsController extends AppController
{
    // all available settings, set in config/app/settings.php
    public $availableSettings;

    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeRender($event);

        $this->availableSettings = $this->DynamicConfig->getAvailableSettings();
        $this->set('availableSettings', $this->availableSettings);

    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('settings', $this->paginate($this->Settings));
        $this->set('_serialize', ['settings']);
    }

    /**
     * Add method
     *
     * @param string|null $name Setting name
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add($name = null)
    {
        // check if there is any name given and that the name is valid
        // return to index if not
        if (empty($name) || !in_array($name, array_keys($this->availableSettings))) {
            return $this->redirect(['action' => 'index']);
        }

        $setting = $this->Settings->newEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));
                // delete settings cache after change
                Cache::delete('settings', 'long');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The setting could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('setting', 'name'));
        $this->set('_serialize', ['setting', 'name']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => [],
        ]);
        $name = $setting['name'];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->data);
            if ($this->Settings->save($setting)) {
                $this->Flash->success(__('The setting has been saved.'));
                // delete settings cache after change
                Cache::delete('settings', 'long');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The setting could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('setting', 'name'));
        $this->set('_serialize', ['setting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        if ($this->Settings->delete($setting)) {
            $this->Flash->success(__('The setting has been deleted.'));
            // delete settings cache after change
            Cache::delete('settings', 'long');
        } else {
            $this->Flash->error(__('The setting could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
