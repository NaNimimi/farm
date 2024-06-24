<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\Customer;
use Validator;

class OrderController extends Controller
{
    public function allOrders()
    {
        $orders = Order::with(['customer', 'doctor', 'service'])->get();
        return response()->json(['orders' => $orders], 200);
    }

    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();

        // Calculate split amounts if price is set
        if (isset($data['price'])) {
            $data['doctor_amount'] = intval($data['price'] / 2);
            $data['cashier_amount'] = intval($data['price'] / 2);
        }

        $order = Order::create($data);
        return response()->json($order, 201);
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['success' => 'Order deleted successfully'], 204);
    }

    public function updateOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();

        // Calculate split amounts if price is set
        if (isset($data['price'])) {
            $data['doctor_amount'] = intval($data['price'] / 2);
            $data['cashier_amount'] = intval($data['price'] / 2);
        }

        $order->update($data);
        return response()->json($order);
    }
}
