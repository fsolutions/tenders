<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class NewOrderPublished extends Notification
{
    use Queueable;

    /**
     * Name of graveyard
     *
     * @var string
     */
    protected $graveyardName;

    /**
     * Order info for telegram
     *
     * @var string
     */
    protected $orderInfoForTelegram;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($graveyardName, $orderInfoForTelegram)
    {
        $this->graveyardName = $graveyardName;
        $this->orderInfoForTelegram = $orderInfoForTelegram;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        return [TelegramChannel::class];
    }

    public function toTelegram($order)
    {
        $tarifInfo = "*Тип: *" . $order->tarif_stringify;
        $uslugiInfo = "*Услуги: *\n" . $this->orderInfoForTelegram;
        $cityInfo = "*Город: *" . $order->city->pagetitle;
        $graveyardInfo = $this->graveyardName != '-' ? "*Кладбище: *" . $this->graveyardName . "\n" : '';
        $costInfo = "*Бюджет: * от " . $order->itogsum . ' руб.';
        $numberOfGraves = "";

        if ($order->tarif == "extended" || $order->tarif == "easy") {
            $numberOfGraves = "*Количество могил на обработку: * " . $order->number_of_graves . "\n";
        }

        $userInfo = '';

        if ($order->opened_order == 1) {
            if ($order->user_web_users_name != '') {
                $userInfo .= '*Имя заказчика: *' . $order->user_web_users_name . "\n";
            }
            if ($order->user_web_users_phone != '') {
                $userInfo .= '*Телефон заказчика: *' . $order->user_web_users_phone . "\n";
            }
            if ($order->user_web_users_phone != '') {
                $userInfo .= '*Email заказчика: *' . $order->user_web_users_email . "\n";
            }

            if ($userInfo != '') {
                $userInfo = '*Открытый тендер!*' . "\n" . $userInfo;
                $userInfo .= "\n";
            }
        }

        return TelegramMessage::create()
            ->to('-1001443430812')
            ->content("*Новый заказ #" . $order->id . "*\n\n" .
                $tarifInfo . "\n" .
                $uslugiInfo . "\n" .
                $cityInfo . "\n" .
                $graveyardInfo . "\n" .
                $numberOfGraves .
                $userInfo .
                $costInfo . "\n\n")
            ->button('Посмотреть заказ', 'https://tenders.gravescare.com/orders/page/' . $order->id);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
