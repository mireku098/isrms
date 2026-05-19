<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check if user is admin - for authorization in controllers
     */
    protected function authorize($ability, $arguments = [])
    {
        if ($ability === 'isAdmin' && !auth()->user()->hasRole('admin')) {
            abort(403, 'Only administrators can access this resource.');
        }
        
        return $this;
    }
}
