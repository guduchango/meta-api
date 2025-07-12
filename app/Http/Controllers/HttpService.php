<?php

namespace App\Http\Controllers;
use App\Http\Exceptions\CustomException;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpService {


    public function sendToWhatsApp($data){
        Log::info("meta data");
        Log::info(json_encode($data));
        $fromPhoneNumberId = config('meta.from_phone_number_id');
        $accessToken = config('meta.access_token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->post(
            config('meta.api_uri') . $fromPhoneNumberId . '/messages',
            $data);

        Log::info("json response");
        Log::info($response->json());
    }
}
