<?php

namespace App\Http\Controllers\API;

use App\User;
use DateTime;
use Validator;
use Carbon\Carbon;
use App\Model\Order;
use App\Mail\SendReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSignOfOrderByClientEnd;
use App\Mail\SendSignOfOrderByClientStart;
use NotificationChannels\Telegram\TelegramMessage;

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
     * Load service list
     *
     * @return void
     */
    public function servicesIndex()
    {
        $services = DB::table('modx_site_content')
            ->where('parent', 21370)
            ->where('published', 1)
            ->orderBy('modx_site_content.pagetitle')
            ->get(['id', 'pagetitle as name']);


        return $services;
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
                'user_web_passport'
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

        $order->order_services = isset($order->order_services) ? json_decode($order->order_services) : ['data' => [], 'itog' => 0];

        if (Auth::user() && Auth::user()->hasRole('administrator')) {
            $order = $order->makeVisible([
                'user_web_users_id',
                'user_web_users_name',
                'user_web_users_phone',
                'user_web_users_email',
                'user_web_passport'
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

        $requestData['paymentid'] = '';
        $requestData['paymenturl'] = '';

        if (isset($requestData['order_txt'])) {
            $requestData['order_txt'] = serialize($requestData['order_txt']);
        }
        if (isset($requestData['order_services'])) {
            $requestData['order_services'] = json_encode($requestData['order_services']);
        }
        $requestData['updatetime'] = time();
        $requestData['order_start'] = time();

        if (!isset($requestData['order_object_name_ext']) || $requestData['order_object_name_ext'] == '') {
            $requestData['order_object_name_ext'] = '';
        }
        // $requestData['user_id'] = $this->userId;

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
        $order->order_services = isset($order->order_services) ? json_decode($order->order_services) : ['data' => [], 'itog' => 0];

        return $order;
        // return response()->json(['data' => $order], 200);
    }

    /**
     * Editting the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderEdit($id)
    {
        $order = Order::find($id);
        $order->order_txt_preped = unserialize($order->order_txt);
        $order->order_services = isset($order->order_services) ? json_decode($order->order_services) : ['data' => [], 'itog' => 0];

        $order->makeVisible([
            'user_web_users_id',
            'user_web_users_name',
            'user_web_users_phone',
            'user_web_users_email',
            'user_web_passport'
        ]);
        return view('orders.create', ['data' => $order]);
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

        if (isset($requestData['updatetime'])) {
            unset($requestData['updatetime']);
        }

        if (isset($requestData['order_txt'])) {
            $requestData['order_txt'] = serialize($requestData['order_txt']);
        }

        //$requestData['order_txt'] = serialize($requestData['order_txt']);
        if (!isset($requestData['order_object_name_ext']) || $requestData['order_object_name_ext'] == '') {
            $requestData['order_object_name_ext'] = '';
        }

        $order = Order::findOrFail($id);
        $order->update($requestData);

        return $order;
    }


    /**
     * Update part of the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePart(Request $request, $id)
    {
        $requestData = $request->all();
        $requestDataPart = [];

        $order = Order::findOrFail($id);

        $letterText = 'Подписан КЛИЕНТОМ заказ-наряд для заказа #' . $order->id;
        $letter = new SendSignOfOrderByClientStart($order->id, $letterText);
        $letterLink = "https://tenders.gravescare.com/orders/client/" . $order->id;

        if (isset($requestData['updatetime'])) {
            unset($requestData['updatetime']);
        }
        if (isset($requestData['signedWithClientStart']) && $requestData['signedWithClientStart'] == 1) {
            $requestDataPart['datetime_of_client_signed_the_start'] = Carbon::now();
            $requestDataPart['status'] = 5; // Заказ исполняется
        } else if (isset($requestData['signedWithClientEnd']) && $requestData['signedWithClientEnd'] == 1) {
            $requestDataPart['datetime_of_client_signed_the_end'] = Carbon::now();
            $requestDataPart['status'] = 9; // Заказ успешно завершен
            $letterText = 'Подписан КЛИЕНТОМ акт для заказа #' . $order->id;
            $letter = new SendSignOfOrderByClientEnd($order->id, $letterText);
            $letterLink = "https://tenders.gravescare.com/orders/client/act/" . $order->id;
        } else {
            abort(404);
        }

        $order->update($requestDataPart);
        Mail::to("info@gravescare.com")->send($letter);

        $message = "<b>" . $letterText . "</b>\n\n" .

            "Ссылка на документ: <a href='" . $letterLink . "'>Перейти »</a>\n";

        @$this->sendToTelegram($message);


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
            'user_web_passport'
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

        $message = "<b>Отклик на заказ #" . $order->id . "</b>\n\n" .
            "ID пользователя: " . $user->id . "\n" .
            "Имя пользователя: " . $user->name . "\n" .
            "Email пользователя: " . $user->email . "\n" .
            "Телефон пользователя: " . $user->phone . "\n\n" .

            "Ссылка на заказ: <a href='https://tenders.gravescare.com/order-edit/" . $order->id . "'>Перейти »</a>\n";


        @$this->sendToTelegram($message);

        return true;
    }

    /**
     * Display the specified resource like zakaz-naryad.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderClientNaryad($id, $additional = '')
    {
        $order = Order::findOrFail($id);

        $order->load(['city', 'graveyard', 'react:id', 'manager:id,name,email,phone']);

        $order->makeVisible([
            'user_web_users_id',
            'user_web_users_name',
            'user_web_users_phone',
            'user_web_users_email',
            'user_web_passport'
        ]);

        $order->order_services = isset($order->order_services) ? json_decode($order->order_services) : ['data' => [], 'itog' => 0];

        return view('orders.orderClient', ['data' => $order, 'additional' => $additional]);
    }

    /**
     * Display the specified resource like act for order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderClientAct($id)
    {
        return $this->orderClientNaryad($id, 'act');
    }

    /**
     * Temp sending to telegram decision
     *
     * @param [type] $message
     * @return void
     */
    public function sendToTelegram($message)
    {
        $token = '1205031121:AAH_FhEjBOCwg_Q7idjJK7-G5H1l5aakMTM';
        $channel_id = '-1001649153212';

        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://api.telegram.org/bot' . $token . '/sendMessage'
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            'chat_id=' . $channel_id . '&text=' . urlencode($message) . '&parse_mode=html'
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return true;
    }
}
