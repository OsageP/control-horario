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

    // Filtros y controladores
    public $entityType = '';
    public $action = '';
    public $userId = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;
    public $search = '';
    public $showDetails = null;
    public $users = [];

    /**
     * Protege el acceso solo para SuperAdmin.
     */
    public function mount()
    {
        abort_unless(Auth::user()->hasRole('SuperAdmin'), 403);
        $this->users = User::select('id', 'name')->get();
    }

    /**
     * Renderiza la vista con paginación y filtros activos.
     */
    public function render()
    {
        $query = $this->buildFilteredQuery();
        $logs = $query->paginate($this->perPage);

        return view('livewire.admin.audit-log-viewer', [
            'logs' => $logs,
        ]);
    }

    /**
     * Construye la query de búsqueda con filtros aplicados.
     */
    protected function buildFilteredQuery()
    {
        return AuditLog::with('actor')
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
    }

    /**
     * Exporta los logs filtrados a CSV o PDF.
     */
    public function export($format)
    {
        $logs = $this->buildFilteredQuery()->get();

        if ($format === 'csv') {
            return new StreamedResponse(function () use ($logs) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Fecha', 'Entidad', 'ID', 'Acción', 'Usuario', 'Descripción']);

                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->entity_type,
                        $log->entity_id,
                        $log->action,
                        $log->actor->name ?? 'Sistema',
                        $log->description ?? '',
                    ]);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="audit_logs.csv"',
            ]);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.audit-log-export', ['logs' => $logs]);
            return response()->streamDownload(fn () => print($pdf->output()), 'audit_logs.pdf');
        }

        abort(400, 'Formato no válido');
    }
}
