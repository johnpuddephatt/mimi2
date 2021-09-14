<?php

namespace App\Nova;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;

use KABBOUCHI\NovaImpersonate\Impersonate;
use LimeDeck\NovaCashierOverview\Subscription;
use OptimistDigital\NovaNotesField\NotesField;
use Alive2tinker\ResourceActivities\ResourceActivities;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'first_name', 'last_name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Image::make('Photo')->disableDownload()->preview(function ($value, $disk) {
                return $value;
            })->thumbnail(function ($value, $disk) {
                return $value;
            })->rounded(),

            Text::make('First name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Last name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Badge::make('Status', function () {
                if($this->subscribed() == 'true') {
                    return 'Active (pay monthly)';
                }
                elseif($this->isCurrent()) {
                    return 'Active';
                }
                else {
                    return null;
                }

            })->map([
                'Subscribed' => 'success',
                'Enrolled' => 'success'
            ])->addTypes([
                null => ''
            ]),

            Number::make('Credits')->step(1)->min(0),

            Boolean::make('Is admin'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            BelongsToMany::make('Classes', 'cohorts', \App\Nova\Cohort::class)->fields(function () {
                return [
                    Boolean::make('Is Subscription Based'),
                ];
            }),

            Subscription::make(),

            Impersonate::make($this)->withMeta([
			    'hideText' => true,
			]),

            NotesField::make('Notes')
            ->placeholder('Add new note') // Optional
            ->addingNotesEnabled(true) // Optional
            ->fullWidth(), // Optional

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
