<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class NewUnworkedOrderRecieved extends Notification
{
    use Queueable;

    /**
     * orderInfo
     *
     * @var Object
     */
    protected $orderInfo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orderInfo)
    {
        $this->orderInfo = $orderInfo;
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

    public function toTelegram()
    {
        $token = '1205031121:AAH_FhEjBOCwg_Q7idjJK7-G5H1l5aakMTM';
        $channel_id = '-1001649153212';

        $messageEnd = "<b>#" . $this->orderInfo->id . ": Новый запрос «" . $this->orderInfo->usluga . "»</b>\n\n" .
            "Заявка со страницы: " . $this->orderInfo->url . "\n\n" .

            "<b>Имя: </b>" . $this->orderInfo->name . "\n" .
            "<b>Телефон: </b>" . $this->orderInfo->phone . "\n\n" .
            // "<a href=\"whatsapp://send?phone=" . $this->orderInfo->phone . "\">Попробовать в WhatsUp >></a>\n\n" .

            "Бюджет: от " . $this->orderInfo->budjet . "\n" .
            "Скидка: " . $this->orderInfo->skidka . "\n" .
            "Регулярность: " . $this->orderInfo->regularnost . "\n\n" .

            "Комментарий: " . $this->orderInfo->comment . "\n";


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
            'chat_id=' . $channel_id . '&text=' . urlencode($messageEnd) . '&parse_mode=HTML'
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return true;

        // WHY IT NOT WORKING????
        return TelegramMessage::create()
            ->to("-1001649153212")
            ->content(
                "*" . $this->orderInfo->id . ": Новый запрос «" . $this->orderInfo->usluga . "»*\n\n" .
                    $messageEnd
            );
        // ->button('Посмотреть заказ', 'https://tenders.gravescare.com/orders/page/' . $order->id);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\orderInfo
     */
    public function toMail($notifiable)
    {
        return (new orderInfo)
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
