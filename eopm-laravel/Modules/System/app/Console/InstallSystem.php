<?php

namespace Modules\System\Console;

use Illuminate\Console\Command;
use Modules\Payment\Models\Payment;
use Illuminate\Support\Facades\Artisan;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Payment\Database\Seeders\PaymentDatabaseSeeder;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;

class InstallSystem extends Command
{
    protected $signature = 'system:install {--fresh : Drop all tables before migrating}';

    protected $description = 'Install system: run ordered migrations and seeders';

    public function handle(): int
    {
        $this->info('Starting system installation...');

        if ($this->option('fresh')) {
            $this->warn('Running migrate:fresh');
            Artisan::call('migrate:fresh', ['--force' => true]);
        }

        $this->runMigrations();
        $this->runSeeders();

        $this->info('System installation completed successfully ✅');

        return self::SUCCESS;
    }

    private function runMigrations(): void
    {
        $this->info('Running migrations in order...');

        $migrationPaths = [
            base_path('Modules/Auth/database/migrations'),
            base_path('Modules/Inventory/database/migrations'),
            base_path('Modules/Order/database/migrations'),
            base_path('Modules/Payment/database/migrations'),
        ];

        foreach ($migrationPaths as $path) {
            $this->line("Migrating: {$path}");

            Artisan::call('migrate', [
                '--path' => $path,
                '--force' => true,
            ]);

            $this->output->write(Artisan::output());
        }
    }

    private function runSeeders(): void
    {
        $this->info('Running seeders in order...');

        $seeders = [
            AuthDatabaseSeeder::class,
            InventoryDatabaseSeeder::class,
            OrderDatabaseSeeder::class,
            PaymentDatabaseSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            $this->line("Seeding: {$seeder}");

            Artisan::call('db:seed', [
                '--class' => $seeder,
                '--force' => true,
            ]);

            $this->output->write(Artisan::output());
        }
    }
}
