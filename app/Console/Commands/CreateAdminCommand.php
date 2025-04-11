<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create new admin account';

    public function handle()
    {
        $this->info('Create new admin account');

        do {
            $username = $this->ask('username:');
            $validator = Validator::make(['username' => $username], [
                'username' => 'required|unique:users,username'
            ]);
            
            if ($validator->fails()) {
                $this->error('Username already exists or invalid!');
            }
        } while ($validator->fails());

        do {
            $email = $this->ask('email:');
            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email|unique:users,email'
            ]);
            
            if ($validator->fails()) {
                $this->error('Email already exists or invalid!');
            }
        } while ($validator->fails());

        do {
            $fullname = $this->ask('fullname:');
            $validator = Validator::make(['fullname' => $fullname], [
                'fullname' => 'required|min:3'
            ]);
            
            if ($validator->fails()) {
                $this->error('Fullname must be at least 3 characters!');
            }
        } while ($validator->fails());

        do {
            $password = $this->secret('password:');
            $validator = Validator::make(['password' => $password], [
                'password' => 'required|min:6'
            ]);
            
            if ($validator->fails()) {
                $this->error('Password must be at least 6 characters!');
            }
        } while ($validator->fails());

        User::create([
            'username' => $username,
            'email' => $email,
            'fullname' => $fullname,
            'password' => Hash::make($password),
            'level' => 1,
            'email_verified_at' => now(),
            'status' => true,
            'uuid' => \Str::uuid()
        ]);

        $this->info('Create account admin success!');
    }
} 