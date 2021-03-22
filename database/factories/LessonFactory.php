<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Week;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
      return [
          'title' => $this->faker->sentence(6),
          'instructions' => $this->faker->paragraph(1),
          'day' => rand(1,5),
          'week_id' => Week::inRandomOrder()->first()->id
      ];
    }
};
