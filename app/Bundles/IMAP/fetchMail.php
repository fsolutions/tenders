<?php

namespace App\Bundles\IMAP;

use DateTime;
use Exception;
use App\Model\InboxMail;

/**
 * Class fetchMail
 */
class fetchMail
{
  /**
   * @var string
   */
  protected $hostname = '{imap.yandex.ru:993/imap/ssl}INBOX';

  /**
   * @var string
   */
  protected $username = 'info@gravescare.com';

  /**
   * @var string
   */
  protected $password = 'zotosqmthkztsvwx';

  /**
   * @var IMAP\Connection
   */
  protected $inbox;

  /**
   * @var array
   */
  protected $newMessages = [];

  public function __construct()
  {
    $this->reconnect();
  }

  /**
   * Fetch New Mail from Inbox
   *
   * @return void
   */
  public function fetchNewMail()
  {
    $date = new DateTime();
    $date->modify("-2 day");
    $searchDate = $date->format("j F Y");

    $this->fetchMailByKeyword('FROM "info@gravescare.com" SINCE "' . $searchDate . '"');
    // $this->fetchMailByKeyword('SUBJECT "Ответ на форму" SINCE "' . $searchDate . '"');

    /* close the connection */
    imap_close($this->inbox);

    $this->sendMailsToTelegram();

    return $this->newMessages;
  }

  /**
   * Fetch Mail by keyword
   *
   * @param String $search
   * 
   * @return void
   */
  public function fetchMailByKeyword($search)
  {
    $emails = imap_search($this->inbox, $search, SE_FREE, 'UTF-8');

    if ($emails) {
      rsort($emails);

      foreach ($emails as $key => $email_number) {
        $this->newMessages[$email_number] = $this->getMessage($email_number);
      }
    }
  }

  /**
   * Returns an associative array with detailed information about a given
   * message.
   *
   * @param $messageId (int)
   *   Message id.
   *
   * @return Associative array with keys (strings unless otherwise noted):
   *   raw_header
   *   to
   *   from
   *   cc
   *   bcc
   *   reply_to
   *   sender
   *   date_sent
   *   subject
   *   deleted (bool)
   *   answered (bool)
   *   draft (bool)
   *   body
   *   original_encoding
   *   size (int)
   *   auto_response (bool)
   *
   * @throws Exception when message with given id can't be found.
   */

  public function _encodeMessage($msg, $type)
  {

    if ($type == 0) {
      return mb_convert_encoding($msg, "UTF-8", "auto");
    } elseif ($type == 1) {
      return imap_8bit($msg); //imap_utf8
    } elseif ($type == 2) {
      return imap_base64(imap_binary($msg));
    } elseif ($type == 3) {
      return imap_base64($msg);
    } elseif ($type == 4) {
      return imap_qprint($msg);
      //return quoted_printable_decode($msg);
    } else {
      return $msg;
    }
  }
  public function getMessage($messageId)
  {
    $this->tickle();

    // Get message details.
    $details = imap_headerinfo($this->inbox, $messageId);
    if ($details) {
      $overview = imap_fetch_overview($this->inbox, $messageId, 0);

      // Get the raw headers.
      $raw_header = imap_fetchheader($this->inbox, $messageId);

      // Detect whether the message is an autoresponse.
      $autoresponse = $this->detectAutoresponder($raw_header);

      // Get some basic variables.
      $deleted = ($details->Deleted == 'D');
      $answered = ($details->Answered == 'A');
      $draft = ($details->Draft == 'X');

      // Get the message body.
      $body = imap_fetchbody($this->inbox, $messageId, 1.2);
      if (!strlen($body) > 0) {
        $body = imap_fetchbody($this->inbox, $messageId, 1);
      }

      // Get the message body encoding.
      $encoding = $this->getEncodingType($messageId);

      // Decode body into plaintext (8bit, 7bit, and binary are exempt).
      if ($encoding == 'BASE64') {
        $body = $this->decodeBase64($body);
      } elseif ($encoding == 'QUOTED-PRINTABLE') {
        $body = $this->decodeQuotedPrintable($body);
      } elseif ($encoding == '8BIT') {
        $body = $this->decode8Bit($body);
      } elseif ($encoding == '7BIT') {
        $body = $this->decode7Bit($body);
      }

      // Build the message.
      $message = array(
        'uid' => $overview[0]->uid,
        'raw_header' => $raw_header,
        'to' => $details->toaddress,
        'from' => $details->fromaddress,
        'cc' => isset($details->ccaddress) ? $details->ccaddress : '',
        'bcc' => isset($details->bccaddress) ? $details->bccaddress : '',
        'reply_to' => isset($details->reply_toaddress) ? $details->reply_toaddress : '',
        'sender' => $details->senderaddress,
        'date_sent' => $details->date,
        'subject' => $details->subject,
        'deleted' => $deleted,
        'answered' => $answered,
        'draft' => $draft,
        'body' => $body,
        'original_encoding' => $encoding,
        'size' => $details->Size,
        'auto_response' => $autoresponse,
      );
    } else {
      throw new Exception("Message could not be found: " . imap_last_error());
    }

    return $message;
  }

  /**
   * Send mail to telegram
   *
   * @return void
   */
  public function sendMailsToTelegram()
  {
    foreach ($this->newMessages as $key => $message) {
      $inboxMail = InboxMail::where('imap_uid', $message['uid'])->first();
      if ($inboxMail == null) {
        $inboxMail = InboxMail::create(['imap_uid' => $message['uid']]);
      }

      if ($inboxMail->sended_to_telegram != 1) {
        $inboxMail->notify(new \App\Notifications\NewMailRecieved($message));
        $inboxMail->sended_to_telegram = 1;
      }

      $inboxMail->save();
    }
  }

  /**
   * Decodes Base64-encoded text.
   *
   * @param $text (string)
   *   Base64 encoded text to convert.
   *
   * @return (string)
   *   Decoded text.
   */
  public function decodeBase64($text)
  {
    $this->tickle();
    return imap_base64($text);
  }

  /**
   * Decodes quoted-printable text.
   *
   * @param $text (string)
   *   Quoted printable text to convert.
   *
   * @return (string)
   *   Decoded text.
   */
  public function decodeQuotedPrintable($text)
  {
    return quoted_printable_decode($text);
  }

  /**
   * Decodes 8-Bit text.
   *
   * @param $text (string)
   *   8-Bit text to convert.
   *
   * @return (string)
   *   Decoded text.
   */
  public function decode8Bit($text)
  {
    return quoted_printable_decode(imap_8bit($text));
  }

  /**
   * Decodes 7-Bit text.
   *
   * PHP seems to think that most emails are 7BIT-encoded, therefore this
   * decoding method assumes that text passed through may actually be base64-
   * encoded, quoted-printable encoded, or just plain text. Instead of passing
   * the email directly through a particular decoding function, this method
   * runs through a bunch of common encoding schemes to try to decode everything
   * and simply end up with something *resembling* plain text.
   *
   * Results are not guaranteed, but it's pretty good at what it does.
   *
   * @param $text (string)
   *   7-Bit text to convert.
   *
   * @return (string)
   *   Decoded text.
   */
  public function decode7Bit($text)
  {
    // If there are no spaces on the first line, assume that the body is
    // actually base64-encoded, and decode it.
    $lines = explode("\r\n", $text);
    $first_line_words = explode(' ', $lines[0]);
    if ($first_line_words[0] == $lines[0]) {
      $text = base64_decode($text);
    }

    // Manually convert common encoded characters into their UTF-8 equivalents.
    $characters = array(
      '=20' => ' ', // space.
      '=2C' => ',', // comma.
      '=E2=80=99' => "'", // single quote.
      '=0A' => "\r\n", // line break.
      '=0D' => "\r\n", // carriage return.
      '=A0' => ' ', // non-breaking space.
      '=B9' => '$sup1', // 1 superscript.
      '=C2=A0' => ' ', // non-breaking space.
      "=\r\n" => '', // joined line.
      '=E2=80=A6' => '&hellip;', // ellipsis.
      '=E2=80=A2' => '&bull;', // bullet.
      '=E2=80=93' => '&ndash;', // en dash.
      '=E2=80=94' => '&mdash;', // em dash.
    );

    // Loop through the encoded characters and replace any that are found.
    foreach ($characters as $key => $value) {
      $text = str_replace($key, $value, $text);
    }
    // dd(mb_convert_encoding($text, "UTF-7", "UTF-8"));

    return $text;
  }

  /**
   * Returns structured information for a given message id.
   *
   * @param $messageId
   *   Message id for which structure will be returned.
   *
   * @return (object)
   *   See imap_fetchstructure() return values for details.
   *
   * @see imap_fetchstructure().
   */
  public function getStructure($messageId)
  {
    return imap_fetchstructure($this->inbox, $messageId);
  }

  /**
   * Returns the primary body type for a given message id.
   *
   * @param $messageId (int)
   *   Message id.
   * @param $numeric (bool)
   *   Set to true for a numerical body type.
   *
   * @return (mixed)
   *   Integer value of body type if numeric, string if not numeric.
   */
  public function getBodyType($messageId, $numeric = false)
  {
    // See imap_fetchstructure() documentation for explanation.
    $types = array(
      0 => 'Text',
      1 => 'Multipart',
      2 => 'Message',
      3 => 'Application',
      4 => 'Audio',
      5 => 'Image',
      6 => 'Video',
      7 => 'Other',
    );

    // Get the structure of the message.
    $structure = $this->getStructure($messageId);

    // Return a number or a string, depending on the $numeric value.
    if ($numeric) {
      return $structure->type;
    } else {
      return $types[$structure->type];
    }
  }

  /**
   * Returns the encoding type of a given $messageId.
   *
   * @param $messageId (int)
   *   Message id.
   * @param $numeric (bool)
   *   Set to true for a numerical encoding type.
   *
   * @return (mixed)
   *   Integer value of body type if numeric, string if not numeric.
   */
  public function getEncodingType($messageId, $numeric = false)
  {
    // See imap_fetchstructure() documentation for explanation.
    $encodings = array(
      0 => '7BIT',
      1 => '8BIT',
      2 => 'BINARY',
      3 => 'BASE64',
      4 => 'QUOTED-PRINTABLE',
      5 => 'OTHER',
    );

    // Get the structure of the message.
    $structure = $this->getStructure($messageId);

    // Return a number or a string, depending on the $numeric value.
    if ($numeric) {
      return $structure->encoding;
    } else {
      return $encodings[$structure->encoding];
    }
  }

  /**
   * Reconnect to the IMAP server.
   *
   * @return (empty)
   *
   * @throws Exception when IMAP can't reconnect.
   */
  private function reconnect()
  {
    $this->inbox = imap_open($this->hostname, $this->username, $this->password, OP_READONLY) or die('Cannot connect to Yandex: ' . imap_last_error());
    if (!$this->inbox) {
      throw new Exception("Reconnection Failure: " . imap_last_error());
    }
  }

  /**
   * Checks to see if the connection is alive. If not, reconnects to server.
   *
   * @return (empty)
   */
  private function tickle()
  {
    if (!imap_ping($this->inbox)) {
      $this->reconnect;
    }
  }

  /**
   * Determines whether the given message is from an auto-responder.
   *
   * This method checks whether the header contains any auto response headers as
   * outlined in RFC 3834, and also checks to see if the subject line contains
   * certain strings set by different email providers to indicate an automatic
   * response.
   *
   * @see http://tools.ietf.org/html/rfc3834
   *
   * @param $header (string)
   *   Message header as returned by imap_fetchheader().
   *
   * @return (bool)
   *   TRUE if this message comes from an autoresponder.
   */
  private function detectAutoresponder($header)
  {
    $autoresponder_strings = array(
      'X-Autoresponse:', // Other email servers.
      'X-Autorespond:', // LogSat server.
      'Subject: Auto Response', // Yahoo mail.
      'Out of office', // Generic.
      'Out of the office', // Generic.
      'out of the office', // Generic.
      'Auto-reply', // Generic.
      'Autoreply', // Generic.
      'autoreply', // Generic.
    );

    // Check for presence of different autoresponder strings.
    foreach ($autoresponder_strings as $string) {
      if (strpos($header, $string) !== false) {
        return true;
      }
    }

    return false;
  }
}
