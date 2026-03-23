<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MarkExistingUsersVerifiedCommand extends Command
{
    protected $signature = 'users:mark-existing-verified {--dry-run : Show count only, do not update}';

    protected $description = 'Set email_verified_at for all users who have not yet verified (one-time migration after enforcing MustVerifyEmail).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $query = User::whereNull('email_verified_at');
        $count = $query->count();

        if ($count === 0) {
            $this->info('No unverified users found.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->info("Would mark {$count} user(s) as verified. Run without --dry-run to apply.");
            return self::SUCCESS;
        }

        $updated = User::whereNull('email_verified_at')->update(['email_verified_at' => now()]);
        $this->info("Marked {$updated} user(s) as verified.");
        return self::SUCCESS;
    }
}
