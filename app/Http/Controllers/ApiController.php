<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Exceptions\CustomException;
use App\Models\WhatsappRequest;
use http\Client\Response;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller {

    private $messageHandler;

    public function __construct() {
        $this->messageHandler = new MessageHandler();
    }

    public function verifyWebhook(Request $request) {
        Log::info("verify pase", $request->all());
        $hub_verify_token = $request->get("hub_verify_token");
        $hub_mode = $request->get("hub_mode");
        $hub_challenge = $request->get("hub_challenge");

        if ($hub_mode === 'subscribe' && $hub_verify_token === config('meta.verify_token')) {
             Log::info("hub_mode token verify", $request->all());
            $wr = new WhatsappRequest();
            $wr->request = json_encode($request->toArray());
            $wr->save();
            return response($hub_challenge, 200);
        } else {
            return response(403);
        }
    }

    public function webhook(Request $request) {
        // Acceder de manera segura al mensaje
        $message = $request['entry'][0]['changes'][0]['value']['messages'][0] ?? null;
        // Acceder de manera segura a la información del remitente
        $senderInfo = $request['entry'][0]['changes'][0]['value']['contacts'][0] ?? null;
        $this->messageHandler->handleIncomingMessage($message, $senderInfo);

        return response(200);
    }

    public function showWebhook() {
        $whatapLast = WhatsappRequest::limit(3)->orderBy('id', 'desc')->get();
        foreach ($whatapLast as $whatap) {
            ss($whatap->request);
        }
    }

    public function getWaitingChats(){
       return $chat = Chat::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();
    }

   public function sendResponse(Request $request)
    {
    // 1. Validación rápida
    $data = $request->validate([
        'id'          => 'required|integer|exists:chats,id',
        'phone'       => 'required|string',
        'response_mje'=> 'required|string',
    ]);

    // 2. Intentar enviar el mensaje y abortar si falla
    try {
        $this->messageHandler->sendResponse($data['phone'], $data['response_mje']);
    } catch (\Throwable $e) {
        Log::error('WhatsApp provider error', ['exception' => $e]);
        return response()->json([
            'status'  => 'error',
            'message' => 'Unable to deliver the message.',
        ], 500);
    }

    // 3. Escrituras en la base dentro de una transacción
    DB::transaction(function () use ($data) {
        Chat::where('id', $data['id'])
            ->update(['status' => 'sent']);

        Chat::create([
            'phone'   => $data['phone'],
            'mje'     => $data['response_mje'],
            'status'  => 'received',
        ]);
    });
    Log::info('Message sent successfully', ['data' => $data]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Message sent successfully.',
    ]);
    }


}
