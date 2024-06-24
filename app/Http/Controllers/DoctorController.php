<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Validator;

class DoctorController extends Controller
{
    public function getAllDoctors()
    {
        $doctors = Doctor::all();
        return response()->json($doctors, 200);
    }

    public function addDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $doctor = Doctor::create($request->all());
        return response()->json(['success' => 'Doctor added successfully', 'doctor' => $doctor], 201);
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $doctor->delete();
        return response()->json(['success' => 'Doctor deleted successfully'], 204);
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer',
            'address' => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $doctor->update($request->all());
        return response()->json($doctor);
    }
}
