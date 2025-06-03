<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionManager extends Component
{
    // Propiedades públicas del componente
    public $selectedUserId;          // ID del usuario seleccionado
    public $userRoles = [];          // Roles asignados al usuario
    public $userPermissions = [];    // Permisos asignados al usuario
    public $roles = [];              // Lista de todos los roles disponibles
    public $permissions = [];        // Lista plana de todos los permisos
    public $groupedPermissions = []; // Permisos agrupados por acción
    public $users = [];              // Lista de usuarios disponibles
    
    // Grupos de acciones y sus etiquetas para mostrar
    public $actionGroups = [
        'view' => 'Ver',
        'create' => 'Crear',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'assign' => 'Asignar',
    ];

    /**
     * Método que se ejecuta al inicializar el componente
     */
    public function mount()
    {
        // Verificar que el usuario tenga permiso para ver roles
        abort_unless(auth()->user()->can('view roles'), 403);

        // Cargar datos iniciales
        $this->loadInitialData();
    }

    /**
     * Carga los datos iniciales necesarios
     */
    protected function loadInitialData()
    {
        // Obtener todos los usuarios
        $this->users = User::all();
        
        // Obtener roles (id => nombre)
        $this->roles = Role::pluck('name', 'id')->toArray();
        
        // Obtener permisos (id => nombre)
        $this->permissions = Permission::pluck('name', 'id')->toArray();
        
        // Agrupar permisos por acción
        $this->groupPermissionsByAction();
    }

    /**
     * Agrupa los permisos por tipo de acción (view, create, edit, etc.)
     */
    protected function groupPermissionsByAction()
    {
        $allPermissions = Permission::all();
        $this->groupedPermissions = []; // Reiniciar array

        // Mapeo de acciones para identificar los diferentes formatos
        $actionMapping = [
            'view' => ['view'],
            'create' => ['create'],
            'edit' => ['edit'],
            'delete' => ['delete'],
            'assign' => ['assign'],
        ];

        foreach ($allPermissions as $permission) {
            $permissionName = strtolower($permission->name);
            
            // Buscar a qué grupo pertenece el permiso
            foreach ($actionMapping as $group => $actions) {
                foreach ($actions as $action) {
                    if (str_contains($permissionName, $action)) {
                        // Extraer la entidad (lo que queda después de quitar la acción)
                        $entity = trim(str_replace($action, '', $permissionName));
                        
                        // Formatear nombre para mostrar (capitalizar y limpiar)
                        $displayName = ucwords(str_replace('-', ' ', $entity));
                        
                        // Inicializar array si no existe
                        if (!isset($this->groupedPermissions[$group])) {
                            $this->groupedPermissions[$group] = [];
                        }
                        
                        // Agregar permiso al grupo correspondiente
                        $this->groupedPermissions[$group][] = [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'entity' => $entity,
                            'display_name' => $displayName
                        ];
                        
                        continue 2; // Pasar al siguiente permiso
                    }
                }
            }
        }
    }

    /**
     * Se ejecuta cuando cambia el usuario seleccionado
     */
    public function updatedSelectedUserId($value)
    {
        if (!$value) {
            // Si no hay usuario seleccionado, limpiar selecciones
            $this->userRoles = [];
            $this->userPermissions = [];
            return;
        }

        // Cargar roles y permisos del usuario seleccionado
        $user = User::find($value);
        $this->userRoles = $user->roles->pluck('id')->toArray();
        $this->userPermissions = $user->permissions->pluck('id')->toArray();
    }

    /**
     * Guarda los cambios de roles y permisos
     */
    public function save()
    {
        // Verificar permisos de edición
        abort_unless(auth()->user()->can('edit roles'), 403);

        $user = User::findOrFail($this->selectedUserId);

        // Obtener nombres de roles y permisos seleccionados
        $roleNames = Role::whereIn('id', $this->userRoles)->pluck('name')->toArray();
        $permissionNames = Permission::whereIn('id', $this->userPermissions)->pluck('name')->toArray();

        // Guardar valores originales para el log
        $originalRoles = $user->getRoleNames()->toArray();
        $originalPermissions = $user->getPermissionNames()->toArray();

        // Actualizar roles y permisos
        $user->syncRoles($roleNames);
        $user->syncPermissions($permissionNames);

        // Registrar en el log de auditoría
        AuditLog::create([
    'entity_type' => 'user', // o User::class si prefieres
    'entity_id' => $user->id,
    'action' => 'update_user_roles_permissions',
    'old_values' => [
        'roles' => $originalRoles,
        'permissions' => $originalPermissions,
    ],
    'new_values' => [
        'roles' => $roleNames,
        'permissions' => $permissionNames,
    ],
    'actor_id' => auth()->id(),
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
    'description' => 'Se actualizaron los roles y permisos del usuario',
]);
        // Mostrar mensaje de éxito
        session()->flash('success', 'Roles y permisos actualizados correctamente.');
    }

    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}