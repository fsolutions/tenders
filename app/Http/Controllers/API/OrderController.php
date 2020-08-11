<?php

namespace App\Http\Controllers\API;

use DateTime;
use Validator;
use App\Model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.orders');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');

        if ($this->userId != 1) {
            $orders = Order::where("user_id", $this->userId)->latest()->paginate($per_page);
        } else {
            $orders = Order::latest()->paginate($per_page);
        }

        $nowDate = new DateTime();
        foreach ($orders as $id => $order) {
            $paidTill = new DateTime($order->paid_till);
            $intervalDiff = $nowDate->diff($paidTill);
            $order->is_paied = true;
            if ((int) $intervalDiff->format('%R%a') < 0 || $order->paid_till == null) {
                $order->is_paied = false;
            }
        }

        return $orders;
    }


    /**
     * Display the specified resource page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.order', ['data' => $order]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'url' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 422);
        // }

        $requestData = $request->all();
        $requestData['user_id'] = $this->userId;

        $order = Order::create($requestData);

        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return $order;
        // return response()->json(['data' => $order], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'url' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 422);
        // }

        $requestData = $request->all();

        $order = Order::findOrFail($id);
        $order->update($requestData);

        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::destroy($id);

        return $order;
    }
}
