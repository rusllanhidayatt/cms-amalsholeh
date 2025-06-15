<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropMigrationsTable extends Command
{
    protected $signature = 'fix:drop-migrations';
    protected $description = 'Force drop corrupted migrations table';

    public function handle()
    {
        try {
            DB::statement("DROP TABLE IF EXISTS `migrations`");
            $this->info("✔️  Table 'migrations' dropped.");
        } catch (\Exception $e) {
            $this->error("❌ Failed: " . $e->getMessage());
        }
    }
}
