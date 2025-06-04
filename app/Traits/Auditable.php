<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAction('created');
        });

        static::updated(function ($model) {
            $model->logAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
    }

    protected function logAction($action)
    {
        $changes = $this->getChangesForAudit();

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'entity_type' => get_class($this),
            'entity_id'   => $this->id,
            'old_values'  => $changes['old'],
            'new_values'  => $changes['new'],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'url'         => request()->fullUrl(),
            'method'      => request()->method(),
        ]);
    }

    protected function getChangesForAudit()
    {
        $old = [];
        $new = [];

        foreach ($this->getDirty() as $key => $value) {
            $old[$key] = $this->getOriginal($key);
            $new[$key] = $value;
        }

        return ['old' => $old, 'new' => $new];
    }
}
