// Sign Up Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const form = document.querySelector('form');
    const emailInput = document.querySelector('input[type="email"]');
    const usernameInput = document.querySelector('input[type="text"]');
    const passwordInput = document.querySelectorAll('input[type="password"]')[0];
    const confirmPasswordInput = document.querySelectorAll('input[type="password"]')[1];
    const termsCheckbox = document.querySelector('input[type="checkbox"]');
    const signupBtn = document.querySelector('.btn');

    // Create message container
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message';
    messageDiv.id = 'message';
    signupBtn.parentNode.insertBefore(messageDiv, signupBtn.nextSibling);

    // Create loading spinner
    const loadingSpinner = document.createElement('div');
    loadingSpinner.className = 'loading-spinner';
    loadingSpinner.id = 'loadingSpinner';
    signupBtn.appendChild(loadingSpinner);

    // Password strength indicator
    const strengthIndicator = document.createElement('div');
    strengthIndicator.className = 'password-strength';
    strengthIndicator.innerHTML = `
        <div class="strength-bar">
            <div class="strength-fill" id="strengthFill"></div>
        </div>
        <span class="strength-text" id="strengthText">Password strength</span>
    `;
    passwordInput.parentNode.appendChild(strengthIndicator);

    // Event Listeners
    emailInput.addEventListener('input', () => validateEmail());
    emailInput.addEventListener('blur', () => validateEmail());
    usernameInput.addEventListener('input', () => validateUsername());
    usernameInput.addEventListener('blur', () => validateUsername());
    passwordInput.addEventListener('input', handlePasswordInput);
    passwordInput.addEventListener('blur', () => validatePassword());
    confirmPasswordInput.addEventListener('input', () => validateConfirmPassword());
    confirmPasswordInput.addEventListener('blur', () => validateConfirmPassword());

    form.addEventListener('submit', handleSignup);

    // Functions
    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = email === '' || emailRegex.test(email);

        if (email && !isValid) {
            emailInput.style.borderColor = '#ff6b6b';
            emailInput.style.boxShadow = '0 0 20px rgba(255, 107, 107, 0.3)';
            showMessage('Please enter a valid email address', 'error');
            return false;
        } else if (email && isValid) {
            emailInput.style.borderColor = '#4ade80';
            emailInput.style.boxShadow = '0 0 20px rgba(74, 222, 128, 0.3)';
            hideMessage();
            return true;
        } else {
            emailInput.style.borderColor = 'rgba(255, 59, 59, 0.3)';
            emailInput.style.boxShadow = 'none';
            return true;
        }
    }

    function validateUsername() {
        const username = usernameInput.value.trim();
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        const isValid = username === '' || usernameRegex.test(username);

        if (username && !isValid) {
            usernameInput.style.borderColor = '#ff6b6b';
            usernameInput.style.boxShadow = '0 0 20px rgba(255, 107, 107, 0.3)';
            showMessage('Username must be 3-20 characters (letters, numbers, underscore only)', 'error');
            return false;
        } else if (username && isValid) {
            usernameInput.style.borderColor = '#4ade80';
            usernameInput.style.boxShadow = '0 0 20px rgba(74, 222, 128, 0.3)';
            hideMessage();
            return true;
        } else {
            usernameInput.style.borderColor = 'rgba(255, 59, 59, 0.3)';
            usernameInput.style.boxShadow = 'none';
            return true;
        }
    }

    function handlePasswordInput() {
        const password = passwordInput.value;
        updatePasswordStrength(password);
        validatePassword();
        if (confirmPasswordInput.value) {
            validateConfirmPassword();
        }
    }

    function validatePassword() {
        const password = passwordInput.value;
        const isValid = password === '' || password.length >= 8;

        if (password && !isValid) {
            passwordInput.style.borderColor = '#ff6b6b';
            passwordInput.style.boxShadow = '0 0 20px rgba(255, 107, 107, 0.3)';
            showMessage('Password must be at least 8 characters', 'error');
            return false;
        } else if (password && isValid) {
            passwordInput.style.borderColor = '#4ade80';
            passwordInput.style.boxShadow = '0 0 20px rgba(74, 222, 128, 0.3)';
            hideMessage();
            return true;
        } else {
            passwordInput.style.borderColor = 'rgba(255, 59, 59, 0.3)';
            passwordInput.style.boxShadow = 'none';
            return true;
        }
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const isValid = confirmPassword === '' || password === confirmPassword;

        if (confirmPassword && !isValid) {
            confirmPasswordInput.style.borderColor = '#ff6b6b';
            confirmPasswordInput.style.boxShadow = '0 0 20px rgba(255, 107, 107, 0.3)';
            showMessage('Passwords do not match', 'error');
            return false;
        } else if (confirmPassword && isValid && password) {
            confirmPasswordInput.style.borderColor = '#4ade80';
            confirmPasswordInput.style.boxShadow = '0 0 20px rgba(74, 222, 128, 0.3)';
            hideMessage();
            return true;
        } else {
            confirmPasswordInput.style.borderColor = 'rgba(255, 59, 59, 0.3)';
            confirmPasswordInput.style.boxShadow = 'none';
            return true;
        }
    }

    function updatePasswordStrength(password) {
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        if (password.length === 0) {
            strengthFill.style.width = '0%';
            strengthFill.style.backgroundColor = '#666';
            strengthText.textContent = 'Password strength';
            strengthText.style.color = '#666';
            return;
        }

        let strength = 0;
        let feedback = [];

        // Length check
        if (password.length >= 8) strength += 25;
        else feedback.push('at least 8 characters');

        // Lowercase check
        if (/[a-z]/.test(password)) strength += 25;
        else feedback.push('lowercase letter');

        // Uppercase check
        if (/[A-Z]/.test(password)) strength += 25;
        else feedback.push('uppercase letter');

        // Number or special character check
        if (/[0-9!@#$%^&*]/.test(password)) strength += 25;
        else feedback.push('number or special character');

        strengthFill.style.width = strength + '%';

        if (strength < 50) {
            strengthFill.style.backgroundColor = '#ff6b6b';
            strengthText.textContent = 'Weak password';
            strengthText.style.color = '#ff6b6b';
        } else if (strength < 75) {
            strengthFill.style.backgroundColor = '#ffa500';
            strengthText.textContent = 'Fair password';
            strengthText.style.color = '#ffa500';
        } else {
            strengthFill.style.backgroundColor = '#4ade80';
            strengthText.textContent = 'Strong password';
            strengthText.style.color = '#4ade80';
        }
    }

    function handleSignup(e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const username = usernameInput.value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        // Validate all fields
        const validations = [
            validateEmail(),
            validateUsername(),
            validatePassword(),
            validateConfirmPassword()
        ];

        if (!termsCheckbox.checked) {
            showMessage('Please accept the terms and conditions', 'error');
            return;
        }

        if (validations.includes(false)) {
            return;
        }

        // Show loading state
        setLoadingState(true);

        // Simulate signup process (replace with actual registration)
        setTimeout(() => {
            // Simulate successful signup
            showMessage('Account created successfully! Welcome!', 'success');

            // Reset loading state
            setLoadingState(false);

            // Clear form
            form.reset();
            updatePasswordStrength('');

            // Redirect after success
            setTimeout(() => {
                window.location.href = '../gender page/gender_page.html';
            }, 1500);

        }, 2500); // Simulate 2.5 second loading
    }

    function setLoadingState(isLoading) {
        signupBtn.disabled = isLoading;
        signupBtn.style.opacity = isLoading ? '0.8' : '1';

        if (isLoading) {
            loadingSpinner.style.display = 'block';
            signupBtn.innerHTML = '<div class="loading-spinner"></div>';
        } else {
            loadingSpinner.style.display = 'none';
            signupBtn.innerHTML = 'Sign Up';
        }
    }

    function showMessage(text, type) {
        messageDiv.textContent = text;
        messageDiv.className = `message ${type}`;
        messageDiv.style.display = 'block';
        messageDiv.style.animation = 'slideDown 0.3s ease-out';
    }

    function hideMessage() {
        messageDiv.style.display = 'none';
    }

    // Add floating animation to background elements
    addBackgroundAnimation();

    function addBackgroundAnimation() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
            }

            body::before {
                animation: float 4s ease-in-out infinite;
            }

            body::after {
                animation: float 6s ease-in-out infinite 2s;
            }
        `;
        document.head.appendChild(style);
    }

    // Add enter key support
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON') {
            signupBtn.click();
        }
    });

    // Add focus effects
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
});