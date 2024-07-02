<?php



namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json($customers);
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching customers'], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info('Creating customer with data: ', $request->all());

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        try {
            $customer = Customer::create($request->all());
            return response()->json(['success' => 'Customer created successfully', 'customer' => $customer], 200);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating customer'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Updating customer with ID ' . $id . ' with data: ', $request->all());

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        try {
            $customer = Customer::findOrFail($id);
            Log::info('Before update: ', $customer->toArray());
            $customer->update($request->all());
            Log::info('After update: ', $customer->toArray());
            return response()->json(['success' => 'Customer updated successfully', 'customer' => $customer], 200);
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating customer'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return response()->json(['success' => 'Customer deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting customer'], 500);
        }
    }
}
