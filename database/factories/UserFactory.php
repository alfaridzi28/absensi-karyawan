<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement([1, 2]), // 1 = admin, 2 = employee
        ];
    }
}
