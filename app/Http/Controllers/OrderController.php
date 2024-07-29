<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock; 
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        $orderDetails = $request->input('order_details');
        $totalAmount = 0;

        foreach ($orderDetails as &$detail) {
            $product = Product::find($detail['product_id']);
            $stock = Stock::where('product_id', $product->id)->first();

            if ($stock->quantity < $detail['quantity']) {
                return redirect()->back()->withErrors(['error' => "The quantity for {$product->name} exceeds the available stock."]);
            }

            $detail['price'] = $product->price;
            $totalAmount += $detail['quantity'] * $detail['price'];
        }

        $order = new Order();
        $order->customer_id = $request->input('customer_id');
        $order->order_date = now();
        $order->total_amount = $totalAmount;
        $order->order_details = json_encode($orderDetails); // Store as JSON
        $order->save();

        // Update stock after saving the order
        foreach ($orderDetails as $detail) {
            $stock = Stock::where('product_id', $detail['product_id'])->first();
            $stock->quantity -= $detail['quantity'];
            $stock->save();
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('customer'); // Load customer relationship
        $order->order_details = json_decode($order->order_details, true); // Decode JSON for view
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $customers = Customer::all();
        $products = Product::all();
        $order->order_details = json_decode($order->order_details, true); // Decode JSON for form
        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        $orderDetails = $request->input('order_details');
        $totalAmount = 0;
        $insufficientStock = [];

        // Decode existing order details
        $existingOrderDetails = json_decode($order->order_details, true);

        // Calculate required stock adjustments
        $stockAdjustments = [];

        foreach ($existingOrderDetails as $existingDetail) {
            if (!isset($stockAdjustments[$existingDetail['product_id']])) {
                $stockAdjustments[$existingDetail['product_id']] = 0;
            }
            $stockAdjustments[$existingDetail['product_id']] += $existingDetail['quantity'];
        }

        foreach ($orderDetails as &$detail) {
            $product = Product::find($detail['product_id']);
            $stock = Stock::where('product_id', $detail['product_id'])->first();

            if (!isset($stockAdjustments[$detail['product_id']])) {
                $stockAdjustments[$detail['product_id']] = 0;
            }
            $stockAdjustments[$detail['product_id']] -= $detail['quantity'];

            if ($stock->quantity + $stockAdjustments[$detail['product_id']] < 0) {
                $insufficientStock[] = $product->name;
            } else {
                $detail['price'] = $product->price;
                $totalAmount += $detail['quantity'] * $detail['price'];
            }
        }

        if (!empty($insufficientStock)) {
            return redirect()->back()->withErrors([
                'insufficient_stock' => 'Insufficient stock for: ' . implode(', ', $insufficientStock)
            ])->withInput();
        }

        // Update order details and total amount
        $order->customer_id = $request->input('customer_id');
        $order->total_amount = $totalAmount;
        $order->order_details = json_encode($orderDetails);
        $order->save();

        // Update stock levels
        foreach ($stockAdjustments as $productId => $adjustment) {
            $stock = Stock::where('product_id', $productId)->first();
            $stock->quantity += $adjustment;
            $stock->save();
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Restore stock quantities
        $orderDetails = json_decode($order->order_details, true);

        foreach ($orderDetails as $detail) {
            $stock = Stock::where('product_id', $detail['product_id'])->first();
            if ($stock) {
                $stock->quantity += $detail['quantity'];
                $stock->save();
            }
        }

        // Delete the order
        $order->delete();
        
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

}
