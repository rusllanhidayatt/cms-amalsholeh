<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageResetLink extends Command
{
    protected $signature = 'storage:reset-link';
    protected $description = 'Reset storage link (remove + re-link public/storage)';

    public function handle(): void
    {
        $publicStoragePath = public_path('storage');

        // Hapus link atau folder lama
        if (is_link($publicStoragePath)) {
            unlink($publicStoragePath);
            $this->info('Old public/storage symlink removed.');
        } elseif (is_dir($publicStoragePath)) {
            File::deleteDirectory($publicStoragePath);
            $this->info('Old public/storage directory removed.');
        }

        // Buat ulang link
        $this->call('storage:link');

        $this->info('Storage link reset completed.');
    }
}