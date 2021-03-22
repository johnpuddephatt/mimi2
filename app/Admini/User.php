<?php

namespace App\Admini;

use Illuminate\Http\Request;
use Admini\app\Resource;

class User extends Resource
{

    public static $model = 'App\\User';

    public static $title = 'User';

    public static $search = ['name','email'];

    // public static $perPage = 10;

    // public static $requestName = 'App\Http\Requests\StoreUser';


    public static function columns()
    {
        return [
                [
                    'name' => 'id',
                    'label' => 'ID',
                    'width' => '40',
                    'numeric' => true,
                    'sortable' => true
                ],
                [
                    'name' => 'name',
                    'label' => 'Name',
                    'filter' => 'lucky',
                    'sortable' => true
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'filter' => "email",
                    'sortable' => true
                ],
                [
                    'name' => 'is_admin',
                    'label' => 'Admin?',
                    'filter' => 'check'
                ]
            ];
    }

    public static function fields()
    {
      return [
              [
                  'name' => 'id',
                  'label' => 'ID',
                  'tab' => 'Public',
                  'inputProps' => [
                    'disabled' => true
                  ]
              ],
              [
                  'name' => 'email',
                  'label' => 'Email',
                  'fieldset' => 'two',
                  'tab' => 'Public',
                  'fieldProps' => [
                    'message' => 'Something useful',
                  ],
                  'inputProps' => [
                    'type' => 'email',
                    'maxlength' => 20
                  ]
              ],
              [
                  'name' => 'name',
                  'label' => 'Name',
                  'fieldset' => 'one',
                  'tab' => 'Public'
              ],
              [
                  'name' => 'password',
                  'label' => 'Password',
                  'fieldset' => 'one',
                  'tab' => 'Public',
                  'inputProps' => [
                    'type' => 'password',
                    'password-reveal' => true
                  ]
              ],
              [
                  'name' => 'is_admin',
                  'fieldset' => 'Something',
                  'label' => 'Admin?',
                  'control' => 'b-checkbox',
                  'tab' => 'Private'
              ]
          ];
    }
}
