<?php

namespace App\Admini;

use Illuminate\Http\Request;
use Admini\app\Resource;

class Course extends Resource
{

    public static $model = 'App\\Course';

    public static $title = 'Course';

    // public static $search = ['name','email'];

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
                    'name' => 'title',
                    'label' => 'Name',
                    'sortable' => true
                ]
            ];
    }

    public static function fields()
    {
      return [
              [
                  'name' => 'id',
                  'label' => 'ID',
                  'inputProps' => [
                    'disabled' => true
                  ]
              ],
              [
                  'name' => 'title',
                  'label' => 'Title',
              ]
          ];
    }
}
