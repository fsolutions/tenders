<?php

namespace App\Http\Controllers\API;

use App\User;
use DateTime;
use Validator;
use App\Model\Order;
use App\Mail\SendReaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * userId
     *
     * @var void
     */
    public $userId;

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
     * @TODO Remove from here to constructor maybe
     *
     * @return void
     */
    public function checkUserId()
    {
        $this->userId = NULL;

        if (Auth::check()) {
            $this->userId = Auth::user()->id;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $this->checkUserId();

        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');

        $orders = Order::orderBy('updatetime', 'DESC')->paginate($per_page);
        $orders->load(['city', 'graveyard', 'react:id']);

        foreach ($orders as $index => $order) {
            foreach ($order->react as $react) {
                $order->reacted = false;
                if ($react->id == $this->userId) {
                    $order->reacted = true;
                }
            }
        }

        if (Auth::user() && Auth::user()->hasRole('administrator')) {
            $orders->data = $orders->makeVisible([
                'user_web_users_id',
                'user_web_users_name',
                'user_web_users_phone',
                'user_web_users_email',
            ]);
            //  Or, $this->setVisible(['example_key']), if this works better for you.
        } else {
            foreach ($orders as $index => $order) {
                if ($order->opened_order == 1) {
                    $order = $order->makeVisible([
                        // 'user_web_users_id',
                        'user_web_users_name',
                        'user_web_users_phone',
                        'user_web_users_email',
                    ]);
                }
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
        $this->checkUserId();

        $order = Order::findOrFail($id);

        $order->load(['city', 'graveyard', 'react:id']);

        foreach ($order->react as $react) {
            $order->reacted = false;
            if ($react->id == $this->userId) {
                $order->reacted = true;
            }
        }

        if (Auth::user() && Auth::user()->hasRole('administrator')) {
            $order = $order->makeVisible([
                'user_web_users_id',
                'user_web_users_name',
                'user_web_users_phone',
                'user_web_users_email',
            ]);
        } else if ($order->opened_order == 1) {
            $order = $order->makeVisible([
                // 'user_web_users_id',
                'user_web_users_name',
                'user_web_users_phone',
                'user_web_users_email',
            ]);
        }

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

    /**
     * Send reaction message to support
     *
     * @param  int  $id order_id
     *
     * @return bool
     */
    public function sendReaction($id)
    {
        $this->checkUserId();

        $order = Order::find($id);
        $order = $order->makeVisible([
            'user_web_users_id',
            'user_web_users_name',
            'user_web_users_phone',
            'user_web_users_email',
        ]);

        Auth::user()->react()->attach($order->id);

        $userToSend = [
            'id' => 'Новый пользователь',
            'name' => 'Новый пользователь',
            'email' => 'Новый пользователь',
            'phone' => 'Новый пользователь'
        ];

        if ($this->userId != NULL) {
            $user = User::find($this->userId);
            $userToSend = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ];
        }

        Mail::to("info@gravescare.com")->send(new SendReaction($order, $userToSend, 'Отклик на заказ #' . $order->id));

        return true;
    }
}
