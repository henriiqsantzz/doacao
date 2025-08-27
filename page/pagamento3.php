<?php
// 👇 INTEGRAÇÃO COM SUA API PHP NO INÍCIO DO ARQUIVO
session_start();
include 'generate_payment.php'; // deve conter a função create_nivopay_transaction()

$user_data = [
    'name' => 'JOAO Victor Moraes',
    'email' => 'caASDiovictorbarrinha@gmail.com',
    'cpf' => '70776355112',
    'phone' => '64994274099',
    'amount' => 7490,
];

if (!isset($_SESSION['pix_code']) || !isset($_SESSION['pix_qr_code'])) {
    $payment_result = create_nivopay_transaction($user_data);
    if ($payment_result['success']) {
        $_SESSION['pix_code'] = $payment_result['pix_code'];
        $_SESSION['pix_qr_code'] = $payment_result['pix_qr_code'];
    } else {
        $_SESSION['pix_code'] = 'Erro ao gerar Pix';
        $_SESSION['pix_qr_code'] = '';
    }
}
$pix_code = $_SESSION['pix_code'];
$pix_qr_code = $_SESSION['pix_qr_code'];
?>

<!-- a partir daqui começa seu HTML normalmente -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pagamento via Pix - Mercado Pago</title>
  <!-- ... SEU CSS ESTÁ INTACTO AQUI ... -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.4;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
        }
        
        /* Header verde */
        .header {
            background-color: #00a650;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
        }
        
        .close-icon {
            width: 24px;
            height: 24px;
            filter: brightness(0) invert(1);
            cursor: pointer;
        }
        
        .header-content {
            flex: 1;
            margin: 0 16px;
        }
        
        .header-title {
            color: white;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Conteúdo principal */
        .main-content {
            padding: 24px 20px;
            background-color: white;
        }
        
        .payment-title {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #333;
        }
        
        .amount {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #00a650;
            margin-bottom: 8px;
        }
        
        .validity-text {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
        }
        
        .timer-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }
        
        .clock-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            filter: invert(0.3) sepia(1) saturate(5) hue-rotate(120deg);
        }
        
        .timer {
            font-size: 18px;
            font-weight: 600;
            color: #00a650;
        }
        
        /* Código Pix */
        .pix-section {
            margin-bottom: 24px;
        }
        
        .pix-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .pix-code-container {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 12px;
            background-color: #f8f9fa;
        }
        
        .pix-code {
            flex: 1;
            font-size: 14px;
            color: #666;
            word-break: break-all;
            margin-right: 12px;
        }
        
        .copy-link {
            color: #3483fa;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .copy-link:hover {
            text-decoration: underline;
        }
        
        .copy-button {
            background-color: #3483fa;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            margin-bottom: 32px;
        }
        
        .copy-button:hover {
            background-color: #2968c8;
        }
        
        /* QR Code */
        .qr-section {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .qr-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .qr-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            display: block;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        
        /* Instruções */
        .instructions {
            padding: 0 4px;
        }
        
        .instruction {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 14px;
            color: #333;
            line-height: 1.4;
        }
        
        .instruction-number {
            font-weight: 600;
            margin-right: 8px;
            min-width: 16px;
        }
        
        .instruction-text {
            flex: 1;
        }
        
        /* Responsividade */
        @media (max-width: 480px) {
            .container {
                max-width: 100%;
            }
            
            .header {
                padding: 16px;
            }
            
            .main-content {
                padding: 20px 16px;
            }
            
            .qr-code {
                width: 180px;
                height: 180px;
            }
        }
    </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
  
      <div class="header-content">
        <div class="header-title">Pague 74,90 via Pix para garantir sua compra</div>
      </div>
      <img src="https://s3.typebot.io/public/workspaces/cm9koxnp3000tb0fu9vivsbw5/typebots/cmcfcff2w000il704qv3ekb4y/blocks/wrp5u0nchbvo9k8ojkhg22aw?v=1753915400971" alt="Panela" class="product-image">
    </div>

    <!-- Conteúdo -->
    <div class="main-content">
      <h1 class="payment-title">Pagamento via Pix</h1>
      <div class="amount">74,90</div>
      <div class="validity-text">Esse código tem validade de 10 minutos</div>
      <div class="timer-container">
        <img src="https://s3.typebot.io/public/workspaces/cm9koxnp3000tb0fu9vivsbw5/typebots/cmcfcff2w000il704qv3ekb4y/blocks/mjxvxyvt7htf9mhl5i2gjll8?v=1753918336806" alt="Relógio" class="clock-icon">
        <div class="timer" id="timer">09:56</div>
      </div>

      <!-- Código Pix dinâmico -->
      <div class="pix-section">
        <div class="pix-label">Código Pix:</div>
        <div class="pix-code-container">
          <div class="pix-code" id="pix-code"><?= htmlspecialchars($pix_code) ?></div>
          <a href="#" class="copy-link" onclick="copyPixCode()">Copiar</a>
        </div>
        <button class="copy-button" onclick="copyPixCode()">Copiar código</button>
      </div>

      <!-- QR Code dinâmico -->
      <div class="qr-section">
        <h2 class="qr-title">Você também pode pagar pelo QR Code Pix</h2>
        <div class="qr-description">Escolha pagar via Pix pelo seu Internet Banking ou app de pagamentos. Depois, filme este código:</div>
        <img src="<?= htmlspecialchars($pix_qr_code) ?>" alt="QR Code Pix" class="qr-code">
      </div>

      <!-- Instruções -->
      <div class="instructions">
        <div class="instruction"><span class="instruction-number">1.</span><span class="instruction-text">Acesse seu Internet Banking ou app de pagamentos.</span></div>
        <div class="instruction"><span class="instruction-number">2.</span><span class="instruction-text">Escolha pagar via Pix.</span></div>
        <div class="instruction"><span class="instruction-number">3.</span><span class="instruction-text">Cole o código Pix e finalize o pagamento.</span></div>
      </div>
    </div>
  </div>

  <script>
    let timeLeft = 9 * 60 + 56;
    function updateTimer() {
      const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
      document.getElementById("timer").textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
      if (timeLeft > 0) timeLeft--;
    }
    setInterval(updateTimer, 1000);

    function copyPixCode() {
      const pixCode = document.getElementById("pix-code").textContent;
      navigator.clipboard.writeText(pixCode).then(() => alert("Código Pix copiado!"));
    }
  </script>
</body>
</html>
