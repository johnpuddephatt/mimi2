<?php

namespace Database\Factories;

use App\Models\Week;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WeekFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Week::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
      $course = Course::inRandomOrder()->first();
      $number = rand(1,10);
      $words = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten'];

      return [
          'name' => 'Week ' . $words[$number - 1],
          'description' => $this->faker->paragraph(1),
          'number' => $number,
          'course_id' => $course->id
      ];
    }
};
