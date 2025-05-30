
<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Livewire\Component;

class AuditLogViewer extends Component
{
    public $logs;

    public function mount()
    {
        $this->logs = AuditLog::with('user')->latest()->take(100)->get();
    }

    public function render()
    {
        return view('livewire.admin.audit-log-viewer');
    }
}
