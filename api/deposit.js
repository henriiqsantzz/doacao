export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method not allowed" });
  }

  const data = req.body || {
    amount: 10,
    paymentMethod: "PIX",
    customerData: {
      name: "João Silva",
      email: "joao@email.com",
      document: "12345678901",
      phone: "+5511999999999",
    },
    metadata: {
      orderId: "ORDER_123",
      description: "Teste de depósito via API",
    },
  };

  try {
    const response = await fetch("https://api.safira.cash/api/payments/deposit", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "x-api-key": "sk_d550eb15592d6c0dc8901197a27786e50282800d7a52bcd9a1f16ca19017a275",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    res.status(200).json(result);
  } catch (err) {
    res.status(500).json({ error: true, message: err.message });
  }
}
