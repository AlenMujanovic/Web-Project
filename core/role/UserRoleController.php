<?php

namespace App\Core\Role;

use App\Core\Controller;

class UserRoleController extends Controller
{
    public function __pre()
    {
        $userId = $this->getSession()->get('user_id');

        if ($userId === null) {
            $this->redirect('/user/login');
        }
    }
}
