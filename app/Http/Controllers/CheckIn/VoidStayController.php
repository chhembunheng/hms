<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\DataTables\CheckIns\VoidStayDataTable;

class VoidStayController extends Controller
{
    public function index(VoidStayDataTable $dataTable)
    {
        return $dataTable->render('check-ins.void-stay.index');
    }
}