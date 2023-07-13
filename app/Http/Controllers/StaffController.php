<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffCollection;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(): StaffCollection
    {
        return new StaffCollection(Staff::query()->paginate(5));
    }

    public function store(Request $request)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
