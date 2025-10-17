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
    
    // Validation en temps réel avec correction
    emailInput.addEventListener('input', function() {
        if (this.value.trim()) {
            validateEmail(this.value, 'emailError');
        } else {
            hideError('emailError');
        }
    });
    
    passwordInput.addEventListener('input', function() {
        if (this.value.trim()) {
            hideError('passwordError'); // Pas de validation stricte pour la connexion
        }
    });
    
    // Clear errors when user starts typing
    emailInput.addEventListener('focus', function() {
        hideError('emailError');
    });
    
    passwordInput.addEventListener('focus', function() {
        hideError('passwordError');
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
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    let isValid = true;
    
    // Clear previous errors
    hideError('emailError');
    hideError('passwordError');
    
    // Simple validation for login
    if (!email) {
        showError('emailError', 'L\'email est requis');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showError('emailError', 'Format d\'email invalide');
        isValid = false;
    }
    
    if (!password) {
        showError('passwordError', 'Le mot de passe est requis');
        isValid = false;
    }
    
    return isValid;
}

// Fonction simplifiée de validation email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
function validateRegisterForm() {
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const userType = document.getElementById('userType').value;
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
    const fieldName = errorId.replace('Error', '');
    const inputElement = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    
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
    const fieldName = errorId.replace('Error', '');
    const inputElement = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    
    if (errorElement) {
        errorElement.classList.remove('show');
        errorElement.textContent = '';
    }
    if (inputElement) {
        inputElement.classList.remove('error');
        inputElement.classList.remove('success');
    }
}
function submitLoginForm() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const submitBtn = document.querySelector('.auth-btn');
    const originalBtnText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
    
    // Clear any previous error messages
    hideError('loginError');
    
    const loginData = {
        email: email,
        password: password
    };
    
    fetch('../api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // ALERT DE DEBUG - À SUPPRIMER APRÈS TEST
            alert('CONNEXION OK!\nRole: ' + (data.user ? data.user.role : 'undefined') + '\nRedirect URL: ' + data.redirect_url);
            
            // Debug temporaire
            console.log('LOGIN DEBUG:', data);
            console.log('Role reçu:', data.debug_role);
            console.log('URL de redirection:', data.redirect_url);
            
            showSuccessMessage('Connexion réussie ! Redirection en cours...');
            setTimeout(() => {
                // Si l'utilisateur est admin, forcer la redirection vers le panel
                if (data.user && data.user.role === 'admin') {
                    alert('ADMIN DETECTE - Redirection vers admin.php');
                    console.log('Admin détecté, redirection forcée vers admin.php');
                    window.location.href = '../view/admin.php';
                    return;
                }
                
                alert('PAS ADMIN - Redirection normale vers: ' + data.redirect_url);
                
                // Utiliser l'URL de redirection fournie par l'API selon le rôle
                if (data.redirect_url) {
                    console.log('Redirection vers:', data.redirect_url);
                    window.location.href = data.redirect_url;
                } else {
                    // Vérifier s'il y a un paramètre de redirection
                    const urlParams = new URLSearchParams(window.location.search);
                    const redirectUrl = urlParams.get('redirect');
                    
                    if (redirectUrl) {
                        window.location.href = decodeURIComponent(redirectUrl);
                    } else {
                        window.location.href = '../view/index.php'; // Redirection vers l'accueil par défaut
                    }
                }
            }, 1500);
        } else {
            showLoginError(data.message || 'Erreur de connexion');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showLoginError('Erreur de connexion. Veuillez réessayer.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
}
function submitRegisterForm() {
    const formData = new FormData(document.getElementById('registerForm'));
    const data = Object.fromEntries(formData);
    const submitBtn = document.querySelector('.auth-btn');
    const originalBtnText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du compte...';
    
    // Clear any previous error messages
    const existingError = document.querySelector('.register-error');
    if (existingError) {
        existingError.classList.remove('show');
    }
    
    const registerData = {
        first_name: data.firstName,
        last_name: data.lastName,
        email: data.email,
        phone: data.phone,
        password: data.password,
        user_type: data.userType
    };
    
    fetch('../api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(registerData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage('Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
        } else {
            showRegisterError(data.message || 'Erreur lors de la création du compte');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showRegisterError('Erreur de connexion. Veuillez réessayer.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
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

function showLoginError(message) {
    const errorElement = document.getElementById('loginError');
    const errorMessageElement = document.getElementById('loginErrorMessage');
    
    if (errorElement && errorMessageElement) {
        errorMessageElement.textContent = message;
        errorElement.classList.add('show');
        
        // Hide after 5 seconds
        setTimeout(() => {
            errorElement.classList.remove('show');
        }, 5000);
    }
}

function showRegisterError(message) {
    let errorElement = document.querySelector('.register-error');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'auth-error register-error';
        errorElement.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <p class="register-error-message"></p>
        `;
        const form = document.querySelector('.auth-form');
        form.appendChild(errorElement);
    }
    
    const messageElement = errorElement.querySelector('.register-error-message');
    if (messageElement) {
        messageElement.textContent = message;
        errorElement.classList.add('show');
        
        // Hide after 5 seconds
        setTimeout(() => {
            errorElement.classList.remove('show');
        }, 5000);
    }
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
