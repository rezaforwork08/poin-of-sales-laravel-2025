<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "All Transaction";
        $datas = [];
        return view('order.index', compact('title', 'datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // ord-tgl-001
        $categories = Category::get();

        $prefix = "ODR";
        $date   = now()->format('dmY');
        // select max from orders
        $lastTransaction = Order::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')->first();

        $lastNumber =  0;

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->order_code, -4);
        }

        $runningNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $order_code = $prefix . "-" . $date . "-" . $runningNumber;

        return view('order.create', compact('categories', 'order_code'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'order_code'  => $request->order_code,
                'order_amount' => $request->subTotal,
                'order_status' => 1,
                'order_subtotal' => $request->grandTotal
            ]);

            foreach ($request->cart as $item) {
                Order_detail::insert([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'qty'    => $item['quantity'],
                    'order_price' => $item['product_price'],
                ]);
            }

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'order_code' => $request->order_code
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'messsage' => $th->getMessage(),
            ], 500);
        }
    }

    public function paymentCashless(Request $request)
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $itemDetails = [];

            foreach ($request->cart as $item) {
                $itemDetails[] = [
                    'id'    => $item['id'],
                    'price' => $item['product_price'],
                    'quantity' => $item['quantity'],
                    'name'  => substr($item['product_name'], 0, 50),
                ];
            }
            
            $payload = [
                'transaction_details' => [
                    'order_id'     => $request->order_code,
                    'gross_amount' => $request->grandTotal
                ],
                'customer_details'  => [
                    'first_name'   => 'Customer',
                    'email'        => 'customer@gmail.com',
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($payload);

            return response()->json([
                'status'  => 'success',
                'snapToken' => $snapToken,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getProducts()
    {
        try {
            $products = Product::with('category')->get();
            return response()->json($products);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Fetch Product failed',
                'status'  => false,
                'error'   => $th->getMessage()
            ], 500);
        }
    }
}
