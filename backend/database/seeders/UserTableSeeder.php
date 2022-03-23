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
        $user = User::where('email', 'teste@teste.com.br')->first();
        if (!$user) {
            User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@teste.com.br',
                'email_verified_at' => now(),
                'password' => bcrypt('123456'),
                'remember_token' => Str::random(10),
                'cpf' => '59679805042',
                'pis' => '54686625900',
                'country' => 'Brasil',
                'state' => 'PR',
                'city' => 'Curitiba',
                'zipcode' => '80770432',
            ]);
        }
    }
}
