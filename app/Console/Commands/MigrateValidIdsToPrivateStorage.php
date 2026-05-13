<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateValidIdsToPrivateStorage extends Command
{
    protected $signature = 'security:migrate-valid-ids {--dry-run : Report files that would be moved without changing storage}';

    protected $description = 'Move uploaded valid ID files from the public disk to private local storage.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $moved = 0;
        $missing = 0;
        $alreadyPrivate = 0;

        User::whereNotNull('valid_id_path')
            ->orderBy('id')
            ->each(function (User $user) use ($dryRun, &$moved, &$missing, &$alreadyPrivate): void {
                $path = $user->valid_id_path;

                if (Storage::disk('local')->exists($path)) {
                    $alreadyPrivate++;
                    return;
                }

                if (!Storage::disk('public')->exists($path)) {
                    $missing++;
                    $this->warn("Missing valid ID for user {$user->id}: {$path}");
                    return;
                }

                if ($dryRun) {
                    $this->line("Would move {$path} for user {$user->id}");
                    $moved++;
                    return;
                }

                Storage::disk('local')->put($path, Storage::disk('public')->get($path));
                Storage::disk('public')->delete($path);
                $moved++;
                $this->info("Moved {$path} for user {$user->id}");
            });

        $this->line("Moved: {$moved}");
        $this->line("Already private: {$alreadyPrivate}");
        $this->line("Missing: {$missing}");

        return self::SUCCESS;
    }
}
