<?php

namespace App\Http\Controllers;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;

class MessageHandler {

    private $whatsappService;
    private $chatFlow;
    private $chat;

    public function __construct()
    {
        $this->whatsappService = new WhatsappService();
        $this->chat = new Chat();
    }

    public function sendResponse($to, $message)
    {
        try{
            $this->whatsappService->sendMessage($to, $message);
        }catch (\Exception $e) {
            Log::error("Error sending message: " . $e->getMessage());
        }
        
    }

    public function handleIncomingMessage($message, $senderInfo)
    {
        Log::info("handleIncomingMessage -> message");
        Log::info($message);
        Log::info("handleIncomingMessage -> senderInfo");
        Log::info($senderInfo);

        Log::info("handleIncomingMessage -> getPhoneNumber");
        Log::info($this->getPhoneNumber($senderInfo));
        Log::info("handleIncomingMessage -> getMsjText");
        Log::info($this->getMsjText($message));
        $mjs = $this->getMsjText($message);
        if (trim($mjs) === "") {
            Log::info("Empty message received, ignoring.");
            return;
        }
        $this->chat->create([
            'phone' => $this->getPhoneNumber($senderInfo),
            'mje' => $this->getMsjText($message),
            'status' => 'waiting'
        ]);

        
        $this->whatsappService->markAsRead($message['id']);
    }

    private function getMessageType($message){
        return $message['type'] ?? "";
    }
    private function getMessageOption($message){
        Log::info("getMessageOption message");
        Log::info($message);
        return $message['interactive']['button_reply']['id'] ?? "";
    }

    private function getSenderName($senderInfo) {
        return $senderInfo['profile']['name'] ?? "Sr/a";
    }

    private function getPhoneNumber($senderInfo){
        return $senderInfo['wa_id'] ?? "000";
    }

    private function getMsjText($message){
        return $message['text']['body']??"";
    }

}
