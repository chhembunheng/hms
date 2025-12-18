<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Settings\ExchangeRateDataTable;

class ExchangeRateController extends Controller
{
    public function index(ExchangeRateDataTable $dataTable)
    {
        return $dataTable->render('settings.exchange-rates.index');
    }

    public function add(Request $request)
    {
        $form = new ExchangeRate();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'from_currency' => 'required|string|max:3',
                'to_currency' => 'required|string|max:3',
                'rate' => 'required|numeric|min:0',
                'effective_date' => 'required|date',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            ExchangeRate::create($request->all());

            return redirect()->route('settings.exchange-rate.index')
                ->with('success', 'Exchange rate created successfully.');
        }

        return view('settings.exchange-rates.add', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = ExchangeRate::findOrFail($id);

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'from_currency' => 'required|string|max:3',
                'to_currency' => 'required|string|max:3',
                'rate' => 'required|numeric|min:0',
                'effective_date' => 'required|date',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $form->update($request->all());

            return redirect()->route('settings.exchange-rate.index')
                ->with('success', 'Exchange rate updated successfully.');
        }

        return view('settings.exchange-rates.edit', compact('form'));
    }

    public function delete($id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        $exchangeRate->delete();

        return redirect()->route('settings.exchange-rate.index')
            ->with('success', 'Exchange rate deleted successfully.');
    }
}
