<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class PurgeOldAuditLogs extends Command
{
    protected $signature = 'audit:purge {--days=365}';
    protected $description = 'Elimina registros de auditoría antiguos';

    public function handle()
    {
        $cutoff = Carbon::now()->subDays($this->option('days'));

        $deleted = AuditLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Se eliminaron {$deleted} registros creados antes del {$cutoff->format('Y-m-d')}.");
    }
}
