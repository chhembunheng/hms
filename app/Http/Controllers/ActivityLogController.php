<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\DataTables\ActivityLogDataTable;

class ActivityLogController extends Controller
{
    public function index(ActivityLogDataTable $dataTable)
    {
        return $dataTable->render('activity-logs.index');
    }

    public function show(ActivityLog $activityLog)
    {
        return view('activity-logs.show', compact('activityLog'));
    }
}
