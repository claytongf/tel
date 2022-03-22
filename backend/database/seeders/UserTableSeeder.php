<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Clayton Figueiredo',
            'email' => 'clayton@techwing.com.br',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
            'cpf' => '03341558942',
            'pis' => '31848505433',
            'country' => 'Brasil',
            'state' => 'PR',
            'city' => 'Curitiba',
            'zipcode' => '80740610',
        ]);
    }
}
