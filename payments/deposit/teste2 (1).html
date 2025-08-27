<?php
header('Content-Type: application/json; charset=utf-8');

// Recebe dados enviados pelo front
$input = json_decode(file_get_contents('php://input'), true);

// Se não vier nada, usa valor padrão
$data = $input ?: [
    "amount" => 10,
    "paymentMethod" => "PIX",
    "customerData" => [
        "name" => "João Silva",
        "email" => "joao@email.com",
        "document" => "12345678901",
        "phone" => "+5511999999999"
    ],
    "metadata" => [
        "orderId" => "ORDER_123",
        "description" => "Teste de depósito via API"
    ]
];

$jsonData = json_encode($data);

$url = 'https://api.safira.cash/api/payments/deposit';
$apiKey = 'sk_d550eb15592d6c0dc8901197a27786e50282800d7a52bcd9a1f16ca19017a275';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        'error' => true,
        'message' => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

echo $response;
