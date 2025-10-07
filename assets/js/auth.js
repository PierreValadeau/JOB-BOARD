document.addEventListener('DOMContentLoaded', function() {
    initializeAuth();
});
function initializeAuth() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    if (loginForm) {
        initializeLoginForm();
    }
    if (registerForm) {
        initializeRegisterForm();
    }
    initializeSocialButtons();
    initializePasswordToggles();
}
function initializeLoginForm() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateLoginForm()) {
            submitLoginForm();
        }
    });
    emailInput.addEventListener('blur', function() {
        validateEmail(this.value, 'emailError');
    });
    passwordInput.addEventListener('blur', function() {
        validatePassword(this.value, 'passwordError', false);
    });
}
function initializeRegisterForm() {
    const form = document.getElementById('registerForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateRegisterForm()) {
            submitRegisterForm();
        }
    });
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });
    passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    confirmPasswordInput.addEventListener('input', function() {
        validatePasswordConfirmation();
    });
}
function validateLoginForm() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    let isValid = true;
    if (!validateEmail(email, 'emailError')) {
        isValid = false;
    }
    if (!validatePassword(password, 'passwordError', false)) {
        isValid = false;
    }
    return isValid;
}
function validateRegisterForm() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const userType = document.getElementById('userType').value;
    const terms = document.getElementById('terms').checked;
    let isValid = true;
    if (!firstName.trim()) {
        showError('firstNameError', 'Le prénom est requis');
        isValid = false;
    } else {
        hideError('firstNameError');
    }
    if (!lastName.trim()) {
        showError('lastNameError', 'Le nom est requis');
        isValid = false;
    } else {
        hideError('lastNameError');
    }
    if (!validateEmail(email, 'emailError')) {
        isValid = false;
    }
    if (!validatePassword(password, 'passwordError', true)) {
        isValid = false;
    }
    if (password !== confirmPassword) {
        showError('confirmPasswordError', 'Les mots de passe ne correspondent pas');
        isValid = false;
    } else {
        hideError('confirmPasswordError');
    }
    if (!userType) {
        showError('userTypeError', 'Veuillez sélectionner votre profil');
        isValid = false;
    } else {
        hideError('userTypeError');
    }
    if (!terms) {
        showError('termsError', 'Vous devez accepter les conditions d\'utilisation');
        isValid = false;
    } else {
        hideError('termsError');
    }
    return isValid;
}
function validateField(input) {
    const value = input.value;
    const name = input.name;
    const errorId = name + 'Error';
    switch (name) {
        case 'firstName':
        case 'lastName':
            if (!value.trim()) {
                showError(errorId, 'Ce champ est requis');
                return false;
            }
            break;
        case 'email':
            return validateEmail(value, errorId);
        case 'phone':
            if (value && !validatePhone(value)) {
                showError(errorId, 'Format de téléphone invalide');
                return false;
            }
            break;
        case 'password':
            return validatePassword(value, errorId, true);
        case 'userType':
            if (!value) {
                showError(errorId, 'Veuillez sélectionner votre profil');
                return false;
            }
            break;
    }
    hideError(errorId);
    return true;
}
function validateEmail(email, errorId) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        showError(errorId, 'L\'email est requis');
        return false;
    }
    if (!emailRegex.test(email)) {
        showError(errorId, 'Format d\'email invalide');
        return false;
    }
    hideError(errorId);
    return true;
}
function validatePhone(phone) {
    const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}
function validatePassword(password, errorId, isRegister = false) {
    if (!password) {
        showError(errorId, 'Le mot de passe est requis');
        return false;
    }
    if (isRegister) {
        if (password.length < 8) {
            showError(errorId, 'Le mot de passe doit contenir au moins 8 caractères');
            return false;
        }
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        if (!hasUpper || !hasLower || !hasNumber) {
            showError(errorId, 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre');
            return false;
        }
    }
    hideError(errorId);
    return true;
}
function validatePasswordConfirmation() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    if (confirmPassword && password !== confirmPassword) {
        showError('confirmPasswordError', 'Les mots de passe ne correspondent pas');
        return false;
    }
    hideError('confirmPasswordError');
    return true;
}
function updatePasswordStrength(password) {
    const strengthBar = document.querySelector('.strength-fill');
    const strengthText = document.querySelector('.strength-text');
    if (!strengthBar || !strengthText) return;
    let score = 0;
    let feedback = '';
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score++;
    strengthBar.className = 'strength-fill';
    switch (score) {
        case 0:
        case 1:
            strengthBar.classList.add('weak');
            feedback = 'Très faible';
            break;
        case 2:
            strengthBar.classList.add('fair');
            feedback = 'Faible';
            break;
        case 3:
            strengthBar.classList.add('good');
            feedback = 'Correct';
            break;
        case 4:
        case 5:
            strengthBar.classList.add('strong');
            feedback = 'Fort';
            break;
    }
    strengthText.textContent = feedback;
}
function showError(errorId, message) {
    const errorElement = document.getElementById(errorId);
    const inputElement = document.querySelector(`[name="${errorId.replace('Error', '')}"]`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }
    if (inputElement) {
        inputElement.classList.add('error');
        inputElement.classList.remove('success');
    }
}
function hideError(errorId) {
    const errorElement = document.getElementById(errorId);
    const inputElement = document.querySelector(`[name="${errorId.replace('Error', '')}"]`);
    if (errorElement) {
        errorElement.classList.remove('show');
    }
    if (inputElement) {
        inputElement.classList.remove('error');
        inputElement.classList.add('success');
    }
}
function submitLoginForm() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;
    const submitBtn = document.querySelector('.auth-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
    setTimeout(() => {
        console.log('Connexion:', { email, password, remember });
        showSuccessMessage('Connexion réussie ! Redirection en cours...');
        setTimeout(() => {
            window.location.href = 'job-ads.html';
        }, 1500);
    }, 2000);
}
function submitRegisterForm() {
    const formData = new FormData(document.getElementById('registerForm'));
    const data = Object.fromEntries(formData);
    const submitBtn = document.querySelector('.auth-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du compte...';
    setTimeout(() => {
        console.log('Inscription:', data);
        showSuccessMessage('Compte créé avec succès ! Un email de confirmation a été envoyé.');
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 2000);
    }, 2500);
}
function initializeSocialButtons() {
    const socialButtons = document.querySelectorAll('.social-btn');
    socialButtons.forEach(button => {
        button.addEventListener('click', function() {
            const provider = this.classList.contains('google-btn') ? 'Google' : 'LinkedIn';
            showNotification(`Connexion avec ${provider} sera bientôt disponible`, 'info');
        });
    });
}
function initializePasswordToggles() {
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            togglePassword(inputId);
        });
    });
}
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.querySelector(`button[onclick*="${inputId}"] i`);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
function showSuccessMessage(message) {
    let successElement = document.querySelector('.success-message');
    if (!successElement) {
        successElement = document.createElement('div');
        successElement.className = 'success-message';
        const form = document.querySelector('.auth-form');
        form.insertBefore(successElement, form.firstChild);
    }
    successElement.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    successElement.classList.add('show');
}
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    const icons = {
        'info': 'fas fa-info-circle',
        'success': 'fas fa-check-circle',
        'warning': 'fas fa-exclamation-triangle',
        'error': 'fas fa-exclamation-circle'
    };
    notification.innerHTML = `
        <i class="${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="closeNotification(this)">&times;</button>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => closeNotification(notification.querySelector('.notification-close')), 4000);
}
function closeNotification(closeBtn) {
    const notification = closeBtn.closest('.notification');
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}
function handleNetworkError() {
    const submitBtn = document.querySelector('.auth-btn');
    submitBtn.disabled = false;
    submitBtn.innerHTML = submitBtn.innerHTML.includes('Connexion') 
        ? '<i class="fas fa-sign-in-alt"></i> Se connecter' 
        : '<i class="fas fa-user-plus"></i> Créer mon compte';
    showNotification('Erreur de connexion. Veuillez réessayer.', 'error');
}
function initializeAutoComplete() {
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = this.value;
            const atIndex = value.indexOf('@');
            if (atIndex > 0 && !value.includes('@', atIndex + 1)) {
                const commonDomains = ['gmail.com', 'yahoo.fr', 'outlook.com', 'orange.fr', 'free.fr'];
                const domain = value.substring(atIndex + 1);
                if (domain.length > 0) {
                    const suggestion = commonDomains.find(d => d.startsWith(domain));
                    if (suggestion) {
                        console.log(`Suggestion: ${value.substring(0, atIndex + 1)}${suggestion}`);
                    }
                }
            }
        });
    });
}
function saveDraft() {
    const form = document.getElementById('registerForm');
    if (!form) return;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    delete data.password;
    delete data.confirmPassword;
    localStorage.setItem('job-finder-register-draft', JSON.stringify(data));
}
function loadDraft() {
    const form = document.getElementById('registerForm');
    if (!form) return;
    const draft = localStorage.getItem('job-finder-register-draft');
    if (!draft) return;
    try {
        const data = JSON.parse(draft);
        Object.entries(data).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = value;
                if (input.type === 'checkbox') {
                    input.checked = value === 'on';
                }
            }
        });
        showNotification('Brouillon restauré', 'info');
    } catch (e) {
        console.error('Erreur lors du chargement du brouillon:', e);
    }
}
function initializeAutoSave() {
    const form = document.getElementById('registerForm');
    if (!form) return;
    const inputs = form.querySelectorAll('input:not([type="password"]), select');
    inputs.forEach(input => {
        input.addEventListener('change', saveDraft);
        input.addEventListener('input', debounce(saveDraft, 1000));
    });
    loadDraft();
}
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
function initializeDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;
    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    });
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
        document.body.classList.add('dark-mode');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    initializeAutoComplete();
    initializeAutoSave();
    initializeDarkMode();
});
function cleanupTempData() {
    localStorage.removeItem('job-finder-register-draft');
}
const notificationStyles = `
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(30, 58, 95, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1000;
    border-left: 4px solid #1e3a5f;
}
.notification.show {
    transform: translateX(0);
}
.notification-info { border-left-color: #1e3a5f; }
.notification-success { border-left-color: #28a745; }
.notification-warning { border-left-color: #ffc107; }
.notification-error { border-left-color: #dc3545; }
.notification-info i { color: #1e3a5f; }
.notification-success i { color: #28a745; }
.notification-warning i { color: #ffc107; }
.notification-error i { color: #dc3545; }
.notification-close {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    margin-left: auto;
    padding: 0;
}
@media (max-width: 768px) {
    .notification {
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}
`;
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
window.togglePassword = togglePassword;
window.closeNotification = closeNotification;
