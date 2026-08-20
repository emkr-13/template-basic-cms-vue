<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:super-admin
                            {--name= : The name of the super admin user}
                            {--email= : The email address of the super admin user}
                            {--password= : The password for the super admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alias for role:init command to initialize permissions and create Super Admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->call('role:init', [
            '--name' => $this->option('name'),
            '--email' => $this->option('email'),
            '--password' => $this->option('password'),
        ]);
    }
}
