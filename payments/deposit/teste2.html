<?php
// Forçar retorno como JSON
header('Content-Type: application/json; charset=utf-8');

// Se quiser receber os dados do front, descomente
// $input = json_decode(file_get_contents('php://input'), true);

// Exemplo de dados da transação (caso não venha nada via POST)
$data = [
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

// Converte para JSON
$jsonData = json_encode($data);

// Endpoint da API
$url = 'https://api.safira.cash/api/payments/deposit';

// Sua chave de API
$apiKey = 'sk_d550eb15592d6c0dc8901197a27786e50282800d7a52bcd9a1f16ca19017a275';

// Inicializa o cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey
]);

// Executa a requisição
$response = curl_exec($ch);

// Trata erros de cURL
if (curl_errno($ch)) {
    echo json_encode([
        'error' => true,
        'message' => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

// Retorna exatamente o JSON recebido da API Safira
echo $response;
