<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      User::factory()->count(30)->create();
      User::factory()->count(2)->create([
        'is_admin' => true
      ]);
      User::factory()->count(1)->create([
        'first_name' => 'John',
        'last_name' => 'Puddephatt',
        'email' => env('ADMIN_EMAIL'),
        'is_admin' => true
      ]);
    }
}
