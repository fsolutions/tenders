<?php

namespace App\Console\Commands;

use App\Bundles\IMAP\fetchMail;
use Illuminate\Console\Command;

/**
 * Class SendMailsToTelegramCommand
 *
 * @package App\Console\Commands
 */
class SendMailsToTelegramCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'orders:sendmailstotelegram';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sending none sended e-mails to telegram';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    echo "\r\n";
    echo "Start searching none sended to telegram e-mails...";
    echo "\r\n";

    $messages = [];

    $fetchingMail = new fetchMail();
    $messages = $fetchingMail->fetchNewMail();

    foreach ($messages as $key => $message) {
      echo $message['subject'] . " - отправлено";
      echo "\r\n";
    }

    echo "\r\n";
    echo "Work is done";
    echo "\r\n";
  }
}
