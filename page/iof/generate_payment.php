<?php

function create_nivopay_transaction($user_data) {
    debug_log("Starting transaction creation for CPF: " . $user_data['cpf']);
    
    $api_base_url = 'https://pay.nivopayoficial.com.br/api/v1';
    $api_token = '74bc8ba1-ed1d-484c-bd9e-89cf59946ede';
    
    $transaction_data = [
        'name' => $user_data['name'],
        'email' => $user_data['email'],
        'cpf' => $user_data['cpf'],
        'phone' => $user_data['phone'],
        'paymentMethod' => 'PIX',
        'amount' => $user_data['amount'],
        'traceable' => true,
        'externalId' => 'CNH_' . $user_data['cpf'] . '_' . time(),
        'postbackUrl' => 'https://yourdomain.com/webhook.php',
        'items' => [
            [
                'unitPrice' => $user_data['amount'],
                'title' => 'Taxa 2025',
                'quantity' => 1,
                'tangible' => false
            ]
        ],
        'cep' => '',
        'complement' => '',
        'number' => '',
        'street' => '',
        'district' => '',
        'city' => '',
        'state' => '',
        'utmQuery' => '',
        'checkoutUrl' => '',
        'referrerUrl' => $_SERVER['HTTP_REFERER'] ?? '',
        'fingerPrints' => []
    ];
    
    debug_log("Transaction data prepared: " . json_encode($transaction_data));
    
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $api_base_url . '/transaction.purchase',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($transaction_data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: ' . $api_token
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    
    curl_close($ch);
    
    debug_log("HTTP Code: $http_code");
    debug_log("cURL Error: " . ($curl_error ?: 'None'));
    debug_log("API Response: " . $response);
    
    if ($curl_error) {
        return [
            'success' => false,
            'error' => 'Erro de conexão: ' . $curl_error
        ];
    }
    
    if ($http_code !== 200) {
        $error_data = json_decode($response, true);
        $error_message = $error_data['message'] ?? 'Erro desconhecido da API';
        
        debug_log("API Error: $error_message");
        
        return [
            'success' => false,
            'error' => "Erro da API (HTTP $http_code): " . $error_message
        ];
    }
    
    $response_data = json_decode($response, true);
    
    if (!$response_data) {
        debug_log("Failed to decode JSON response");
        return [
            'success' => false,
            'error' => 'Resposta inválida da API'
        ];
    }
    
    if (empty($response_data['pixCode']) || empty($response_data['id'])) {
        debug_log("Missing PIX data in response: " . json_encode($response_data));
        return [
            'success' => false,
            'error' => 'Dados PIX não encontrados na resposta da API'
        ];
    }
    
    debug_log("Transaction created successfully - ID: " . $response_data['id']);
    
    return [
        'success' => true,
        'transaction_id' => $response_data['id'],
        'pix_code' => $response_data['pixCode'],
        'pix_qr_code' => $response_data['pixQrCode'] ?? $response_data['pixCode'],
        'status' => $response_data['status'] ?? 'PENDING',
        'expires_at' => $response_data['expiresAt'] ?? null,
        'amount' => $response_data['amount'] ?? $user_data['amount'],
        'custom_id' => $response_data['customId'] ?? null,
        'full_response' => $response_data
    ];
}

?>