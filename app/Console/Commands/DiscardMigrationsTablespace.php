<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiscardMigrationsTablespace extends Command
{
    protected $signature = 'fix:discard-migrations';
    protected $description = 'Attempt to discard tablespace for broken `migrations` table';

    public function handle()
    {
        try {
            DB::statement("DROP TABLE IF EXISTS `migrations`");
            $this->info("✔️ Table dropped successfully.");
        } catch (\Exception $e) {
            $this->warn("❌ DROP failed: " . $e->getMessage());
            $this->warn("🔁 Trying to DISCARD TABLESPACE...");

            try {
                DB::statement("ALTER TABLE `migrations` DISCARD TABLESPACE");
                DB::statement("DROP TABLE `migrations`");
                $this->info("✔️ Tablespace discarded and table dropped.");
            } catch (\Exception $ex) {
                $this->error("❌ DISCARD failed: " . $ex->getMessage());
            }
        }
    }
}
