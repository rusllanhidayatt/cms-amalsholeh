<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageCleanPublic extends Command
{
    protected $signature = 'storage:clean-public';
    protected $description = 'Delete all files & folders in storage/app/public';

    public function handle(): void
    {
        $path = storage_path('app/public');

        if (File::exists($path)) {
            File::cleanDirectory($path);
            $this->info('All files & folders in storage/app/public have been deleted.');
        } else {
            $this->warn('Directory does not exist: ' . $path);
        }
    }
}
