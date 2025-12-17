<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Users data
        $users = [
            [
                'name'    => 'robert delacruz',
                'email'       => 'delacruz@gmail.com',
                'password'    => password_hash('robert123', PASSWORD_DEFAULT),
                'role'        => 'admin',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'theamarie justalero',
                'email'       => 'theamarie@gmail.com',
                'password'    => password_hash('theamarie4', PASSWORD_DEFAULT),
                'role'        => 'teacher',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'ezelann olaver',
                'email'       => 'ezelann@gmail.com',
                'password'    => password_hash('ezelann', PASSWORD_DEFAULT),
                'role'        => 'student',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'milky recla',
                'email'       => 'milky@gmail.com',
                'password'    => password_hash('milky', PASSWORD_DEFAULT),
                'role'        => 'teacher',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'rachel adam',
                'email'       => 'rachel@gmail.com',
                'password'    => password_hash('rachel', PASSWORD_DEFAULT),
                'role'        => 'student',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],

        ];

        $this->db->table('users')->insertBatch($users);
    }
}