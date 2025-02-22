<?php

namespace App\Models\Factories;

use App\Entities\User;
use App\Models\UserModel;
use Faker\Generator;

class UserFactory extends UserModel
{
    /**
     * Factory method to create a fake user for testing.
     */
    public function fake(Generator &$faker): User
    {
        return new User([
            'username' => $this->generateUniqueUsername($faker->userName()),
            'name'     => $faker->name(),
            'email'    => $faker->email(),
            'password' => $faker->password(),
            'active'   => true,
            'country'  => $faker->countryCode(),
            'timezone' => $faker->timezone(),
            'trust_level' => 0,
        ]);
    }

    private function generateUniqueUsername(string $username): string
    {
        $username = url_title($username, '-', true);

        if ($this->where('username', $username)->first()) {
            helper('text');
            $username .= '-' . random_string('alnum', 4);
        }

        return $username;
    }
}
