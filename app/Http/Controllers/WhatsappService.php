<?php

namespace App\Http\Controllers;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService {

    private $httpService;

    public function __construct()
    {
        $this->httpService = new HttpService();
    }

    public function sendMessage($to, $body, $messageId = null)
    {
        $to = $this->cleanPhoneNumber($to);
        Log::info("clean to");
        Log::info($to);
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $body],
        ];

        Log::info("data");
        Log::info(json_encode($data));

        $this->httpService->sendToWhatsApp($data);
    }

    public function sendInteractiveButtons($to, $bodyText, $buttons)
    {
        $to = $this->cleanPhoneNumber($to);
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $bodyText],
                'action' => ['buttons' => $buttons],
            ],
        ];

        $this->httpService->sendToWhatsApp($data);
    }

    public function sendMediaMessage($to, $type, $mediaUrl, $caption = null)
    {
        $mediaObject = [];

        switch ($type) {
            case 'image':
                $mediaObject['image'] = ['link' => $mediaUrl, 'caption' => $caption];
                break;
            case 'audio':
                $mediaObject['audio'] = ['link' => $mediaUrl];
                break;
            case 'video':
                $mediaObject['video'] = ['link' => $mediaUrl, 'caption' => $caption];
                break;
            case 'document':
                $mediaObject['document'] = ['link' => $mediaUrl, 'caption' => $caption, 'filename' => 'medpet-file.pdf'];
                break;
            default:
                throw new Exception('Not Supported Media Type');
        }

        $data = array_merge([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => $type,
        ], $mediaObject);

        $this->httpService->sendToWhatsApp($data);
    }

    public function markAsRead($messageId)
    {
        $data = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ];

        $this->httpService->sendToWhatsApp($data);
    }

    public function sendContactMessage($to, $contact)
    {
        $to = $this->cleanPhoneNumber($to);
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'contacts',
            'contacts' => [$contact],
        ];

        $this->httpService->sendToWhatsApp($data);
    }

    public function sendLocationMessage($to, $latitude, $longitude, $name, $address)
    {
        $to = $this->cleanPhoneNumber($to);
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'location',
            'location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'name' => $name,
                'address' => $address,
            ],
        ];

        $this->httpService->sendToWhatsApp($data);
    }

    private function cleanPhoneNumber($number) {
        return strpos($number, '549') === 0 ? str_replace('549', '54', $number) : $number;
    }

}
