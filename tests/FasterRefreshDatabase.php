<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Finder\Finder;

trait FasterRefreshDatabase
{
    use RefreshDatabase;

    /**
     * Only migrates fresh if necessary
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->runMigrationsIfNecessary();

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    protected function runMigrationsIfNecessary(): void
    {
        if (Schema::hasTable('migrations')) {
            $migrated = DB::table('migrations')
                ->orderBy('migration')
                ->get('migration')
                ->pluck('migration')
                ->toArray();

            $files = array_map(function ($file) {
                return str_replace('.php', '', pathinfo($file, PATHINFO_FILENAME));
            }, File::files(base_path('/database/migrations')));

            $diff = array_diff($files, $migrated);

            if (empty($diff) && $this->identicalChecksum()) {
                // migrations table has records for all migrations
                // no migrations have been edited since last run
                return;
            }
        }

        // run migrations and generate checksum of current state of files
        $this->artisan('migrate:fresh', $this->migrateFreshUsing());
        $this->createChecksum();
    }

    private function calculateChecksum(): string
    {
        $files = Finder::create()
            ->files()
            ->in(database_path('migrations'))
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->getIterator();

        $files = array_keys(iterator_to_array($files));

        $checksum = collect($files)->map(function ($file) {
            return md5_file($file);
        })->implode('');

        return md5($checksum);
    }

    private function checksumFilePath(): string
    {
        return base_path('.phpunit.database.checksum');
    }

    private function createChecksum(): void
    {
        file_put_contents($this->checksumFilePath(), $this->calculateChecksum());
    }

    private function checksumFileContents(): string|bool
    {
        return file_get_contents($this->checksumFilePath());
    }

    private function isChecksumExists(): bool
    {
        return file_exists($this->checksumFilePath());
    }

    private function identicalChecksum(): bool
    {
        if (!$this->isChecksumExists()) {
            return false;
        }

        if ($this->checksumFileContents() === $this->calculateChecksum()) {
            return true;
        }

        return false;
    }
}
