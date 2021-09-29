<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Authorises
{
  public function checkModelPermissions()
  {
    if (Auth::user()->hasRole('administrator')) {
      $this->setVisible([
        'user_web_users_id',
        'user_web_users_name',
        'user_web_users_phone',
        'user_web_users_email',
      ]);
      //  Or, $this->setVisible(['example_key']), if this works better for you.
    }

    return parent::checkModelPermissions();
  }
}
