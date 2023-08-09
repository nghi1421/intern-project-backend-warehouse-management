<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Enums\ImportStatus;
use App\Models\Export;
use App\Models\Import;
use App\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeneratePDFController extends Controller
{
    public function import(string $importId, Request $request)
    {
        // $user = $request->user();

        // if ($user->canAny(['manage-import', 'read-branch-import'])) {
        //     $import = Import::query()->find($importId);
        //     if (!$import) {
        //         abort(404);
        //     }

        //     if ($user->can('read-branch-import') && $user->cant('manage-import')) {
        //         $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

        //         if ($staff->warehouse_branch_id !== $import->warehouse_branch_id) {
        //             abort(403);
        //         }
        //     }

        //     $sum = 0;

        //     $categories = array_map(function ($category) use ($sum) {
        //         $quantity = $category['pivot']['quantity'];
        //         $unitPrice = $category['pivot']['unit_price'];
        //         $sum += $quantity * $unitPrice;
        //         unset($category['pivot']);
        //         return [
        //             ...$category,
        //             'amount' => $quantity,
        //             'unit_price' => $unitPrice
        //         ];
        //     }, $import->categories()->select(['id', 'name', 'unit'])->get()->toArray());

        //     $importData =  [
        //         'id' => $import->id,
        //         'staff' => $import->staff->toArray(),
        //         'staff_name' => $import->staff->name,
        //         'staff_position' => $import->staff->position->name,
        //         'provider_name' => $import->provider->name,
        //         'categories' => $categories,
        //         'provider' => $import->provider->toArray(),
        //         'status' => ImportStatus::tryFrom($import->status)->label(),
        //         'sum' => $sum,
        //         'created_at' => $import->created_at->format('Y-m-d H:i:s'),
        //     ];
        //     $pdf = PDF::loadView('import', $importData);

        //     $pdf->setPaper('A4', 'landscape');

        //     return $pdf->stream('import.pdf');
        // }

        // abort(403);

        $import = Import::query()->find($importId);

        if (!$import) {
            abort(404);
        }

        $exportDetails = $import->categories()->select(['id', 'name', 'unit'])->get()->toArray();

        $sum = collect($exportDetails)->reduce(function ($sum, $category) {
            return $sum + $category['pivot']['quantity'] * $category['pivot']['unit_price'];
        }, 0);

        $categories = array_map(function ($category) {
            $quantity = $category['pivot']['quantity'];
            $unitPrice = $category['pivot']['unit_price'];
            unset($category['pivot']);
            return [
                ...$category,
                'amount' => number_format($quantity, 2, ".", ","),
                'unit_price' => number_format($unitPrice, 0, ".", ","),
            ];
        }, $exportDetails);

        $importData =  [
            'id' => $import->id,
            'staff' => $import->staff->toArray(),
            'staff_name' => $import->staff->name,
            'staff_position' => $import->staff->position->name,
            'staff_dob' => Carbon::create($import->staff->dob)->format('d/m/Y'),
            'categories' => $categories,
            'provider' => $import->provider->toArray(),
            'warehouse_branch' => $import->warehouseBranch->toArray(),
            'status' => ImportStatus::tryFrom($import->status)->label(),
            'sum' => number_format($sum, 0, ".", ",") . ' VND',
            'created_at' => $import->created_at->format('H:i:s d/m/Y'),
        ];
        $pdf = PDF::loadView('import', $importData);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream($this->generatePDFFileName('import', $import->id));
    }

    public function export(string $exportId, Request $request)
    {
        $export = Export::query()->find($exportId);

        if (!$export) {
            abort(404);
        }

        $exportDetails = $export->categories()->select(['id', 'name', 'unit'])->get()->toArray();


        $categories = array_map(function ($category) {
            $quantity = $category['pivot']['quantity'];
            unset($category['pivot']);
            return [
                ...$category,
                'amount' => number_format($quantity, 2, ".", ","),
            ];
        }, $exportDetails);

        $exportData =  [
            'id' => $export->id,
            'staff' => $export->staff->toArray(),
            'staff_position' => $export->staff->position->name,
            'staff_dob' => Carbon::create($export->staff->dob)->format('d/m/Y'),
            'categories' => $categories,
            'warehouse_branch' => $export->warehouseBranch->toArray(),
            'status' => Export::tryFrom($export->status)->label(),
            'created_at' => $export->created_at->format('H:i:s d/m/Y'),
        ];
        $pdf = PDF::loadView('export', $exportData);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('export.pdf');
    }

    protected function generatePDFFileName(string $prefix, string $id): string
    {
        return $prefix . '_' . $id . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
    }
}
