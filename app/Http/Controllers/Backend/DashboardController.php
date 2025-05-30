<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ControlPrevio;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['dashboard.view']);

        return view(
            'backend.pages.dashboard.index',
            [
                'total_admins' => Admin::count(),
                'total_roles' => Role::count(),
                'total_permisos' => Permission::count(),
                'total_controles_previos' => ControlPrevio::count(),
            ]
        );
    }
}
