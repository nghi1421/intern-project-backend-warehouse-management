<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function import(Request $request)
    {
        $user = $request->user();

        if ($user->role->name === 'Thu kho') {
            $staff = Staff::query('user_id', $user->getKey());

            $importLog = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->orWhere('from_warehouse_branch_id', $staff->warehouse_branch_id)
                ->with('categories')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
