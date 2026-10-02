// Forgot Password Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const form = document.querySelector('form');
    const emailInput = document.querySelector('input[type="email"]');
    const resetBtn = document.querySelector('.btn');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const backLink = document.querySelector('.back-link a');
    const subtitle = document.querySelector('.subtitle');

    // Typing animation for subtitle
    const subtitleText = "Enter your email address and we'll send you a link to reset your password.";
    let charIndex = 0;

    function typeWriter() {
        if (charIndex < subtitleText.length) {
            subtitle.innerHTML = subtitleText.substring(0, charIndex + 1) + '<span class="typing"></span>';
            charIndex++;
            setTimeout(typeWriter, 50);
        } else {
            subtitle.innerHTML = subtitleText;
        }
    }

    // Start typing animation after a short delay
    setTimeout(typeWriter, 500);

    // Event Listeners
    emailInput.addEventListener('input', () => validateEmail());
    emailInput.addEventListener('blur', () => validateEmail());
    form.addEventListener('submit', handleResetPassword);
    backLink.addEventListener('click', handleBackToLogin);

    // Functions
    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = email === '' || emailRegex.test(email);

        if (email && !isValid) {
            emailInput.style.borderColor = '#ff6b6b';
            emailInput.style.boxShadow = '0 0 20px rgba(255, 107, 107, 0.3)';
            showError('Please enter a valid email address');
            return false;
        } else if (email && isValid) {
            emailInput.style.borderColor = '#4ade80';
            emailInput.style.boxShadow = '0 0 20px rgba(74, 222, 128, 0.3)';
            hideMessages();
            return true;
        } else {
            emailInput.style.borderColor = 'rgba(255, 59, 59, 0.3)';
            emailInput.style.boxShadow = 'none';
            return true;
        }
    }

    function handleResetPassword(e) {
        e.preventDefault();

        const email = emailInput.value.trim();

        // Validate email
        if (!validateEmail() || !email) {
            showError('Please enter a valid email address');
            emailInput.focus();
            return;
        }

        // Show loading state
        setLoadingState(true);

        // Simulate sending reset email (replace with actual API call)
        setTimeout(() => {
            // Simulate successful email sending
            showSuccess();

            // Reset loading state
            setLoadingState(false);

            // Clear form
            emailInput.value = '';

            // Auto redirect after 5 seconds
            setTimeout(() => {
                window.location.href = 'sign_in.html';
            }, 5000);

        }, 2000); // Simulate 2 second loading
    }

    function handleBackToLogin(e) {
        e.preventDefault();
        // Add click animation
        backLink.style.transform = 'scale(0.95)';
        setTimeout(() => {
            backLink.style.transform = 'scale(1)';
            // Redirect to sign in page
            window.location.href = 'sign_in.html';
        }, 150);
    }

    function setLoadingState(isLoading) {
        resetBtn.disabled = isLoading;
        resetBtn.style.opacity = isLoading ? '0.8' : '1';

        if (isLoading) {
            resetBtn.innerHTML = '<div class="loading-spinner"></div>';
        } else {
            resetBtn.innerHTML = 'Send Reset Link';
        }
    }

    function showSuccess() {
        hideMessages();
        successMessage.style.display = 'block';
        successMessage.style.animation = 'fadeInUp 0.5s ease-out';
        createParticles();
    }

    // Create particle effect
    function createParticles() {
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 2 + 's';
            document.body.appendChild(particle);

            // Remove particle after animation
            setTimeout(() => {
                particle.remove();
            }, 3000);
        }
    }

    function showError(message) {
        hideMessages();
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        errorMessage.style.animation = 'fadeInUp 0.5s ease-out';
    }

    function hideMessages() {
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
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
            resetBtn.click();
        }
    });

    // Add focus effects
    emailInput.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });

    emailInput.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });

    // Add typing animation for subtitle
    addTypingAnimation();

    function addTypingAnimation() {
        const subtitle = document.querySelector('.subtitle');
        const text = subtitle.textContent;
        subtitle.textContent = '';
        subtitle.style.borderRight = '2px solid #ff9898';

        let i = 0;
        const timer = setInterval(() => {
            if (i < text.length) {
                subtitle.textContent += text.charAt(i);
                i++;
            } else {
                clearInterval(timer);
                setTimeout(() => {
                    subtitle.style.borderRight = 'none';
                }, 500);
            }
        }, 50);
    }

    // Add particle effect for success message
    function addParticleEffect() {
        const particles = [];
        const particleCount = 20;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: #4ade80;
                border-radius: 50%;
                pointer-events: none;
                animation: particleFloat 2s ease-out forwards;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                animation-delay: ${Math.random() * 2}s;
            `;
            document.body.appendChild(particle);
            particles.push(particle);

            setTimeout(() => {
                particle.remove();
            }, 2000);
        }
    }

    // Show particles when success message appears
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                if (successMessage.style.display === 'block') {
                    addParticleEffect();
                }
            }
        });
    });

    observer.observe(successMessage, {
        attributes: true,
        attributeFilter: ['style']
    });
});