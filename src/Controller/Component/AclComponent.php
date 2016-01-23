<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class AclComponent extends Component
{

    public $controller = null;
    public $session = null;

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->controller = $this->_registry->getController();

        $this->session = $this->controller->request->session();

    }

    public function __call($method, $arguments)
    {
        return [$method, $arguments];
    }

}
