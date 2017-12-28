<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class AdminController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;


    protected function dataTableSorting(&$query, $colums, $orders) {
        foreach($orders as $order) {
            if (isset($colums[$order['column']])) {
                $query->orderBy($colums[$order['column']]['name'], $order['dir']);
            }
        }
    }
}
