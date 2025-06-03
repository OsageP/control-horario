<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditLogViewer extends Component
{
    use WithPagination;

    public $entityType = '';
    public $action = '';
    public $userId = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;
    public $search = '';
    public $showDetails = null;
    public $users = [];
    public $toastMessage = null;
    public $toastType = 'success'; // Valores: success, warning, error

    protected $queryString = [
        'entityType', 'action', 'userId', 'dateFrom', 'dateTo', 'search'
    ];

    public function mount()
    {
        abort_unless(Auth::user()->hasRole('SuperAdmin'), 403);
        $this->users = User::select('id', 'name')->get();
    }

    public function updating($property)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AuditLog::with('actor')
            ->when($this->entityType, fn($q) => $q->where('entity_type', $this->entityType))
            ->when($this->action, fn($q) => $q->where('action', $this->action))
            ->when($this->userId, fn($q) => $q->where('actor_id', $this->userId))
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->search, fn($q) => $q->where(function ($q2) {
                $q2->where('description', 'like', "%{$this->search}%")
                    ->orWhere('entity_type', 'like', "%{$this->search}%")
                    ->orWhere('action', 'like', "%{$this->search}%");
            }))
            ->latest();

        return view('livewire.admin.audit-log-viewer', [
            'logs' => $query->paginate($this->perPage),
        ]);
    }

// Limpia todos los filtros
public function resetFilters()
{
    $this->entityType = '';
    $this->action = '';
    $this->userId = '';
    $this->dateFrom = '';
    $this->dateTo = '';
    $this->search = '';
    $this->toastMessage = 'Filtros reiniciados correctamente.';
}

// Exportación con aviso
public function export($format)
{
    $logs = AuditLog::with('actor')
        ->when($this->entityType, fn($q) => $q->where('entity_type', $this->entityType))
        ->when($this->action, fn($q) => $q->where('action', $this->action))
        ->when($this->userId, fn($q) => $q->where('actor_id', $this->userId))
        ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
        ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
        ->when($this->search, fn($q) => $q->where(function ($q2) {
            $q2->where('description', 'like', "%{$this->search}%")
                ->orWhere('entity_type', 'like', "%{$this->search}%")
                ->orWhere('action', 'like', "%{$this->search}%");
        }))
        ->latest()
        ->get();

    if ($logs->isEmpty()) {
        $this->toastMessage = 'No hay registros para exportar.';
        return null;
    }

    if ($format === 'csv') {
        $this->toastMessage = 'Exportación CSV generada.';
        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Fecha', 'Entidad', 'ID', 'Acción', 'Usuario', 'Descripción']);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    $log->entity_type,
                    $log->entity_id,
                    $log->action,
                    $log->actor->name ?? 'Sistema',
                    $log->description,
                ]);
            }
            fclose($handle);
        }, 'audit_logs.csv');
    }

    if ($format === 'pdf') {
        $this->toastMessage = 'Exportación PDF generada.';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.audit-log-export', ['logs' => $logs]);
        return response()->streamDownload(fn () => print($pdf->output()), 'audit_logs.pdf');
    }

    abort(400, 'Formato no válido');
}
}
