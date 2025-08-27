// Aguarda o carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do formulário
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const continueBtn = document.querySelector('.continue-btn');
    const createAccountLink = document.querySelector('.create-account-link');

    // Função para validar email/telefone/usuário
    function validateInput(value) {
        // Remove espaços em branco
        value = value.trim();
        
        // Verifica se não está vazio
        if (value.length === 0) {
            return false;
        }
        
        // Verifica se é um email válido, telefone ou usuário
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[\d\s\-\+\(\)]+$/;
        const userRegex = /^[a-zA-Z0-9._-]+$/;
        
        return emailRegex.test(value) || phoneRegex.test(value) || userRegex.test(value);
    }

    // Função para atualizar o estado do botão
    function updateButtonState() {
        const inputValue = emailInput.value.trim();
        const isValid = validateInput(inputValue);
        
        if (isValid && inputValue.length > 0) {
            continueBtn.disabled = false;
            continueBtn.style.opacity = '1';
            continueBtn.style.cursor = 'pointer';
        } else {
            continueBtn.disabled = true;
            continueBtn.style.opacity = '0.6';
            continueBtn.style.cursor = 'not-allowed';
        }
    }

    // Event listener para mudanças no input
    emailInput.addEventListener('input', updateButtonState);
    emailInput.addEventListener('keyup', updateButtonState);
    emailInput.addEventListener('paste', function() {
        // Aguarda um pouco para o valor ser colado
        setTimeout(updateButtonState, 10);
    });

    // Event listener para o formulário
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const inputValue = emailInput.value.trim();
        
        if (validateInput(inputValue)) {
            // Simula o comportamento de login
            console.log('Tentativa de login com:', inputValue);
            
            // Aqui você pode adicionar a lógica real de autenticação
            // Por exemplo, fazer uma requisição para o servidor
            
            // Feedback visual temporário
            continueBtn.textContent = 'Carregando...';
            continueBtn.disabled = true;
            
            // Simula um delay de processamento
            setTimeout(function() {
                continueBtn.textContent = 'Continuar';
                continueBtn.disabled = false;
                alert('Funcionalidade de login não implementada nesta demonstração.');
            }, 1500);
        } else {
            // Mostra erro de validação
            emailInput.style.borderColor = '#ff6b6b';
            emailInput.focus();
            
            // Remove o erro após 3 segundos
            setTimeout(function() {
                emailInput.style.borderColor = '#e6e6e6';
            }, 3000);
        }
    });

    // Event listener para o link "Criar conta"
    createAccountLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Funcionalidade de criação de conta não implementada nesta demonstração.');
    });

    // Foco inicial no campo de input
    emailInput.focus();

    // Estado inicial do botão
    updateButtonState();

    // Adiciona efeitos visuais extras
    emailInput.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });

    emailInput.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});

// Função para detectar se é mobile
function isMobile() {
    return window.innerWidth <= 768;
}

// Ajustes para mobile
window.addEventListener('resize', function() {
    // Pode adicionar ajustes específicos para mudanças de orientação
    if (isMobile()) {
        // Ajustes específicos para mobile
        document.body.classList.add('mobile');
    } else {
        document.body.classList.remove('mobile');
    }
});

// Executa a verificação inicial
if (isMobile()) {
    document.body.classList.add('mobile');
}

