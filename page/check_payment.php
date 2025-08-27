<?php

header('Content-Type: application/json');

if (!function_exists('debug_log')) {
    function debug_log($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents('debug.txt', "[$timestamp] CHECK_PAYMENT: $message\n", FILE_APPEND | LOCK_EX);
    }
}

function get_payment_status($transaction_id) {
    debug_log("Checking payment status for transaction: $transaction_id");
    
    $api_base_url = 'https://pay.nivopayoficial.com.br/api/v1';
    $api_token = '74bc8ba1-ed1d-484c-bd9e-89cf59946ede';
    
    $url = $api_base_url . '/transaction.getPaymentDetails?id=' . urlencode($transaction_id);
    
    debug_log("Making request to: $url");
    
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $api_token
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 15,
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
        debug_log("cURL error occurred: $curl_error");
        return [
            'success' => false,
            'error' => 'Erro de conexão: ' . $curl_error,
            'status' => 'error'
        ];
    }
    
    if ($http_code !== 200) {
        $error_data = json_decode($response, true);
        $error_message = $error_data['message'] ?? 'Erro desconhecido da API';
        
        debug_log("API Error (HTTP $http_code): $error_message");
        
        return [
            'success' => false,
            'error' => "Erro da API (HTTP $http_code): " . $error_message,
            'status' => 'error'
        ];
    }
    
    $response_data = json_decode($response, true);
    
    if (!$response_data) {
        debug_log("Failed to decode JSON response");
        return [
            'success' => false,
            'error' => 'Resposta inválida da API',
            'status' => 'error'
        ];
    }
    

    $status = $response_data['status'] ?? 'PENDING';
    $mapped_status = map_nivopay_status($status);
    
    debug_log("Payment status: $status (mapped to: $mapped_status)");
    
    return [
        'success' => true,
        'status' => $mapped_status,
        'original_status' => $status,
        'transaction_id' => $response_data['id'] ?? $transaction_id,
        'amount' => $response_data['amount'] ?? 0,
        'customer' => $response_data['customer'] ?? [],
        'method' => $response_data['method'] ?? 'PIX',
        'created_at' => $response_data['createdAt'] ?? null,
        'updated_at' => $response_data['updatedAt'] ?? null,
        'expires_at' => $response_data['expiresAt'] ?? null,
        'due_at' => $response_data['dueAt'] ?? null,
        'full_response' => $response_data
    ];
}

function map_nivopay_status($nivopay_status) {
   
    $status_map = [
        'PENDING' => 'pending',
        'APPROVED' => 'paid',
        'PAID' => 'paid',
        'CANCELLED' => 'cancelled',
        'REJECTED' => 'rejected',
        'REFUNDED' => 'refunded',
        'CHARGEBACK' => 'chargeback',
        'EXPIRED' => 'expired'
    ];
    
    return $status_map[strtoupper($nivopay_status)] ?? 'pending';
}




try {
 
    $transaction_id = $_GET['transaction_id'] ?? '';
    $cpf = $_GET['cpf'] ?? '';
    
    debug_log("=== PAYMENT CHECK REQUEST ===");
    debug_log("Transaction ID: $transaction_id");
    debug_log("CPF: $cpf");
    
    if (empty($transaction_id)) {
        debug_log("Missing transaction_id parameter");
        echo json_encode([
            'success' => false,
            'error' => 'ID da transação é obrigatório',
            'status' => 'error'
        ]);
        exit;
    }
    
    if (empty($cpf)) {
        debug_log("Missing CPF parameter");
        echo json_encode([
            'success' => false,
            'error' => 'CPF é obrigatório',
            'status' => 'error'
        ]);
        exit;
    }
    

    $result = get_payment_status($transaction_id);
    
    if ($result['success'] && isset($result['customer']['cpf'])) {
        $response_cpf = preg_replace('/\D/', '', $result['customer']['cpf']);
        $request_cpf = preg_replace('/\D/', '', $cpf);
        
        if ($response_cpf !== $request_cpf) {
            debug_log("CPF mismatch: Request CPF ($request_cpf) != Response CPF ($response_cpf)");
            echo json_encode([
                'success' => false,
                'error' => 'CPF não confere com a transação',
                'status' => 'error'
            ]);
            exit;
        }
    }
    
    debug_log("Final result: " . json_encode($result));
    echo json_encode($result);
    
} catch (Exception $e) {
    debug_log("Exception occurred: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro interno do servidor: ' . $e->getMessage(),
        'status' => 'error'
    ]);
}

debug_log("=== PAYMENT CHECK END ===");
?>