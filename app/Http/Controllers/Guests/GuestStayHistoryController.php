<?php

namespace App\Http\Controllers\Guests;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuestStayHistoryController extends Controller
{
    public function index($guestId)
    {
        $guest = Guest::with(['checkIns' => function($query) {
            $query->with(['room.roomType', 'room.floor'])
                  ->orderBy('check_in_date', 'desc')
                  ->orderBy('created_at', 'desc');
        }])->findOrFail($guestId);

        return view('guests.stay-history.index', compact('guest'));
    }
}
