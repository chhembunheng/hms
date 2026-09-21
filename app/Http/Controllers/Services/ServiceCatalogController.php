<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceCatalogController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $query = Service::query();

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $services = $query->orderBy('category')->orderBy('name_en')->get();

        return view('services.catalog.index', compact('services', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:services,code,' . $request->id,
            'name_en' => 'required|string|max:255',
            'name_kh' => 'nullable|string|max:255',
            'category' => 'required|in:tour,transport,laundry,spa,minibar,fnb,other',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:30',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('id')) {
            $service = Service::findOrFail($request->id);
            $service->update($validated);
            $message = 'Service updated successfully.';
        } else {
            Service::create($validated);
            $message = 'Service created successfully.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'redirect' => route('services.catalog.index')
        ]);
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Service deleted successfully.',
            'redirect' => route('services.catalog.index')
        ]);
    }
}
