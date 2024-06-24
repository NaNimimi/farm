<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Validator;

class ServiceController extends Controller
{
    public function getAllServices()
    {
        $services = Service::with('category')->get();
        return response()->json($services, 200);
    }

    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $service = Service::create($request->all());
        return response()->json(['success' => 'Service added successfully', 'service' => $service], 201);
    }

    public function deleteService($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['success' => 'Service deleted successfully'], 204);
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $service->update($request->all());
        return response()->json($service);
    }
}
