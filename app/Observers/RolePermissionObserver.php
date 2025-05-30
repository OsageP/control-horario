<?php

namespace App\Observers;

use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionObserver
{
    public function created($model)
    {
        $this->log('created', $model);
    }

    public function updated($model)
    {
        $this->log('updated', $model);
    }

    public function deleted($model)
    {
        $this->log('deleted', $model);
    }

    protected function log($action, $model)
    {
        AuditLog::create([
            'user_id' => auth()->id() ?? null,
            'actor_id' => auth()->id() ?? null,
            'action' => $action,
            'target_model' => get_class($model),
            'target_id' => $model->id,
            'changes' => $model->getChanges(),
        ]);
    }
}
