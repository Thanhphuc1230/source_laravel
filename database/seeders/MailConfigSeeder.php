<?php

namespace Database\Seeders;

use App\Models\MailConfig;
use Illuminate\Database\Seeder;

class MailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default mail configuration (inactive by default)
        MailConfig::create([
            'mailer' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'your-email@gmail.com',
            'password' => 'your-app-password',
            'encryption' => 'tls',
            'from_address' => 'noreply@yourdomain.com',
            'from_name' => 'Your App Name',
            'is_active' => false,
        ]);

        // Create alternative configuration for testing
        MailConfig::create([
            'mailer' => 'smtp',
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'username' => 'your-mailtrap-username',
            'password' => 'your-mailtrap-password',
            'encryption' => 'tls',
            'from_address' => 'test@yourdomain.com',
            'from_name' => 'Test App',
            'is_active' => false,
        ]);
    }
}
