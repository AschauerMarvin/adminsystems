<?php

namespace App\View\Helper;

use Cake\View\Helper;

class AclHelper extends Helper
{

    public function __call($method, $arguments)
    {
        // make first letter uppercase
        // index, Index should both work
        if (!empty($arguments[0])) {
            $arguments[0] = ucfirst($arguments[0]);
        }

        // if no arguments are passed, combined mode is active
        $combined = false;
        if (empty($arguments[0])) {
            $combined = true;
        }

        // check if the user is admin
        // administrators always have access
        $admin = $this->request->session()->read('Auth.User.admin');
        if (!empty($admin) && $admin == 1) {
            return true;
        }

        // if combined mode is not active, check for the single permission
        if (!$combined) {
            // check if the users role has the requested permission
            $perm = $this->request->session()->read('Auth.Role.permissions.' . $method . '.' . $arguments[0]);
            if (!empty($perm) && $perm == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            // in the combined mode, check if ANY of the requested permissions is set
            $perm = $this->request->session()->read('Auth.Role.permissions.' . $method);
            if (!empty($perm) && is_array($perm)) {
                foreach ($perm as $p) {
                    if ($p) {
                        // sub-permission found, return true
                        return true;
                    }
                }
            }
        }
        // return false if nothing was found
        return false;
    }

}
