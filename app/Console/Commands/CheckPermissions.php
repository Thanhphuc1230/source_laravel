<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\User::where('email', 'admin@gmail.com')->first();
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $this->info('Checking permissions for admin@gmail.com');

        $permissions = ['site_setting.view', 'system.view'];
        foreach ($permissions as $perm) {
            $has = $user->hasPermission($perm);
            $this->info("$perm: " . ($has ? 'YES' : 'NO'));
        }
    }
}
