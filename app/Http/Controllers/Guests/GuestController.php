<?php

namespace App\Http\Controllers\Guests;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Guests\GuestDataTable;

class GuestController extends Controller
{
    public function index(GuestDataTable $dataTable)
    {
        return $dataTable->render('guests.guests.index');
    }

    public function add(Request $request)
    {
        $form = new Guest();

        if ($request->isMethod('post')) {
            $rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:guests',
                'phone' => 'nullable|string|max:20',
                'national_id' => 'nullable|string|max:50|unique:guests',
                'passport' => 'nullable|string|max:50|unique:guests',
                'guest_type' => 'nullable|in:national,international',
                'country' => 'nullable|string|max:100',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $data = $request->only([
                'first_name', 'last_name', 'email', 'phone', 'national_id', 'passport',
                'guest_type', 'country', 'date_of_birth', 'gender', 'address', 'city',
                'state', 'postal_code', 'emergency_contact_name', 'emergency_contact_phone', 'notes'
            ]);

            Guest::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Guest created successfully.',
                'redirect' => route('guests.list.index'),
                'delay' => 2000
            ]);
        }

        return view('guests.guests.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = Guest::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:guests,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'national_id' => 'nullable|string|max:50|unique:guests,national_id,' . $id,
                'passport' => 'nullable|string|max:50|unique:guests,passport,' . $id,
                'guest_type' => 'nullable|in:national,international',
                'country' => 'nullable|string|max:100',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $data = $request->only([
                'first_name', 'last_name', 'email', 'phone', 'national_id', 'passport',
                'guest_type', 'country', 'date_of_birth', 'gender', 'address', 'city',
                'state', 'postal_code', 'emergency_contact_name', 'emergency_contact_phone', 'notes'
            ]);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Guest updated successfully.',
                'redirect' => route('guests.list.index'),
                'delay' => 2000
            ]);
        }

        return view('guests.guests.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $guest = Guest::findOrFail($id);

            // Check if guest has active check-ins
            if ($guest->checkIns()->active()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete guest because they have active check-ins.'
                ]);
            }

            $guest->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Guest deleted successfully.',
                'redirect' => route('guests.list.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete guest: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $guest = Guest::with(['checkIns.room', 'checkIns.room.roomType'])->findOrFail($id);

        return view('guests.guests.show', compact('guest'));
    }

    public function toggleBlacklist(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'is_blacklisted' => 'required|boolean',
                'blacklist_reason' => 'required_if:is_blacklisted,1|string|max:1000',
                'internal_notes' => 'nullable|string|max:2000',
            ];

            $request->validate($rules);

            $data = $request->only(['is_blacklisted', 'blacklist_reason', 'internal_notes']);

            if ($request->boolean('is_blacklisted')) {
                $data['blacklisted_at'] = now();
            } else {
                $data['blacklist_reason'] = null;
                $data['blacklisted_at'] = null;
            }

            $guest->update($data);

            return response()->json([
                'status' => 'success',
                'message' => $request->boolean('is_blacklisted') ? 'Guest blacklisted successfully.' : 'Guest removed from blacklist.',
                'redirect' => route('guests.list.show', $guest->id),
                'delay' => 2000
            ]);
        }

        return view('guests.guests.blacklist-form', compact('guest'));
    }

    public function updateNotes(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'notes' => 'nullable|string|max:1000',
                'internal_notes' => 'nullable|string|max:2000',
            ];

            $request->validate($rules);

            $guest->update($request->only(['notes', 'internal_notes']));

            return response()->json([
                'status' => 'success',
                'message' => 'Guest notes updated successfully.',
                'redirect' => route('guests.list.show', $guest->id),
                'delay' => 2000
            ]);
        }

        return view('guests.guests.notes-form', compact('guest'));
    }
}
