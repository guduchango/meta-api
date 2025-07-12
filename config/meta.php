<?php

return [
    'api_uri' => env('WHATSAPP_API_URI'),
    'whatsapp_business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    'access_token' => env('ACCESS_TOKEN'),
    'separator' => env('SEPARATOR', '~'),
    'from_phone_number_id' => env('FROM_PHONE_NUMBER_ID'),
    'verify_token' => env('VERIFY_TOKEN'),
];
