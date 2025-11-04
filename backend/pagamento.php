<?php
// Credenciais Sandbox
$merchantId = 'ae4bd42a-e04e-477d-978e-36494d03e8b7';
$merchantKey = '2nnUWOSaY9M9WQqUx4mlQB2QWGvHyK85B72DfSB6';

// Endpoint Sandbox Braspag (Gateway Cielo)
$url = 'https://apisandbox.braspag.com.br/v2/sales/';

// Corpo da requisição (simulando pagamento com cartão de crédito)
$data = [
    "MerchantOrderId" => "2025001",
    "Customer" => [
        "Name" => $_POST['nome_titular'],
        "Identity" => $_POST['cpf_titular']
    ],
    "Payment" => [
        "Provider" => "Simulado",
        "Type" => "CreditCard",
        "Amount" => intval(round($_POST['preco'] * 100)),
        "Installments" => 1,
        "Capture" => true,
        "CreditCard" => [
            "CardNumber" => $_POST['numero_cartao'],
            "Holder" => $_POST['nome_titular'],
            "ExpirationDate" => $_POST['validade'],
            "SecurityCode" => $_POST['cvv'],
            "Brand" => $_POST['bandeira']
        ]
    ]
];

// Inicializa cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "MerchantId: $merchantId",
    "MerchantKey: $merchantKey"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Mostra retorno completo da API
echo "<h3>Retorno da API:</h3>";
echo "<p><strong>Status HTTP:</strong> $httpCode</p>";
echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
?>