<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidateEnvCommand extends Command
{
    protected $signature = 'env:validate';

    protected $description = 'Validate required environment variables. Exit with 1 if any are missing or invalid.';

    public function handle(): int
    {
        $required = [
            'APP_KEY' => env('APP_KEY'),
            'APP_ENV' => env('APP_ENV'),
            'DB_CONNECTION' => config('database.default'),
            'DB_HOST' => config('database.connections.' . config('database.default') . '.host'),
            'DB_DATABASE' => config('database.connections.' . config('database.default') . '.database'),
            'DB_USERNAME' => config('database.connections.' . config('database.default') . '.username'),
            'DB_PASSWORD' => config('database.connections.' . config('database.default') . '.password'),
        ];

        $failed = [];

        if (empty($required['APP_KEY']) || strlen($required['APP_KEY']) < 20) {
            $failed[] = 'APP_KEY must be set and at least 20 characters (run php artisan key:generate)';
        }

        if (empty($required['APP_ENV'])) {
            $failed[] = 'APP_ENV must be set';
        }

        if (empty($required['DB_CONNECTION'])) {
            $failed[] = 'DB_CONNECTION must be set';
        }

        if (empty($required['DB_HOST'])) {
            $failed[] = 'DB_HOST must be set';
        }

        if (empty($required['DB_DATABASE'])) {
            $failed[] = 'DB_DATABASE must be set';
        }

        if ($required['DB_USERNAME'] === null || $required['DB_USERNAME'] === '') {
            $failed[] = 'DB_USERNAME must be set';
        }

        // DB_PASSWORD can be empty for some DBs; only require key exists in config (no failure if empty)

        if (! empty($failed)) {
            foreach ($failed as $message) {
                $this->error($message);
            }
            $this->warn('Fix the above and run php artisan env:validate again.');
            return self::FAILURE;
        }

        $this->info('Required environment variables are set.');
        return self::SUCCESS;
    }
}
