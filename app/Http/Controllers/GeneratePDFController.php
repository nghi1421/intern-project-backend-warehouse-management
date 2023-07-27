<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImportResource;
use App\Models\Enums\ImportStatus;
use App\Models\Import;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePDFController extends Controller
{
    public function import(string $importId)
    {
        $import = Import::query()->find($importId);

        if (!$import) {
            abort(404);
        }

        $categories = array_map(function ($category) {
            $quantity = $category['pivot']['quantity'];
            $unitPrice = $category['pivot']['unit_price'];
            unset($category['pivot']);
            return [
                ...$category,
                'amount' => $quantity,
                'unit_price' => $unitPrice
            ];
        }, $import->categories()->select(['id', 'name', 'unit'])->get()->toArray());

        $importData =  [
            'id' => $import->id,
            'staff' => $import->staff->toArray(),
            'staff_name' => $import->staff->name,
            'staff_position' => $import->staff->position->name,
            'provider_name' => $import->provider->name,
            'categories' => $categories,
            'provider' => $import->provider->toArray(),
            'status' => ImportStatus::tryFrom($import->status)->label(),
            'created_at' => $import->created_at->format('Y-m-d H:i:s'),
        ];
        $pdf = PDF::loadView('import', $importData);
        //Nếu muốn hiển thị file pdf theo chiều ngang
        $pdf->setPaper('A4', 'landscape');

        //Nếu muốn download file pdf
        // return $pdf->download('report.pdf');
        // return view('import');
        //Nếu muốn preview in pdf
        return $pdf->stream('import.pdf');
    }
}