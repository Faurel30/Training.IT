// Learn More Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.menu a[href^="#"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards and sections for animation
    const animatedElements = document.querySelectorAll('.program-card, .why-card, .testimonial-card');

    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            navbar.style.transform = 'translateY(0)';
        }

        // Add background blur on scroll
        if (scrollTop > 50) {
            navbar.style.background = 'rgba(0, 0, 0, 0.9)';
        } else {
            navbar.style.background = 'rgba(0, 0, 0, 0.8)';
        }

        lastScrollTop = scrollTop;
    });

    // Program card hover effects
    const programCards = document.querySelectorAll('.program-card');

    programCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // CTA button ripple effect
    const ctaButtons = document.querySelectorAll('.cta-primary-btn, .cta-secondary-btn');

    ctaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (e.offsetX - 10) + 'px';
            ripple.style.top = (e.offsetY - 10) + 'px';
            ripple.style.width = '20px';
            ripple.style.height = '20px';

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add ripple animation to CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Stats counter animation
    const stats = document.querySelectorAll('.stat h3');
    let hasAnimated = false;

    function animateStats() {
        if (hasAnimated) return;

        stats.forEach(stat => {
            const target = parseInt(stat.textContent.replace(/[^\d]/g, ''));
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(current).toLocaleString() + (stat.textContent.includes('+') ? '+' : stat.textContent.includes('%') ? '%' : '');
            }, 20);
        });

        hasAnimated = true;
    }

    // Trigger stats animation when hero section is visible
    const heroSection = document.querySelector('.hero-section');
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(animateStats, 500);
            }
        });
    }, { threshold: 0.5 });

    heroObserver.observe(heroSection);

    // Parallax effect for hero background
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroBg = document.querySelector('.hero-bg');

        if (heroBg) {
            heroBg.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // Escape key to close any open modals (if implemented later)
        if (e.key === 'Escape') {
            // Close modal logic here
        }

        // Tab navigation improvements
        if (e.key === 'Tab') {
            const focusedElement = document.activeElement;
            if (focusedElement.classList.contains('cta-primary-btn') ||
                focusedElement.classList.contains('cta-secondary-btn')) {
                focusedElement.style.outline = '2px solid #ff3b3b';
            }
        }
    });

    // Add touch support for mobile
    let touchStartY = 0;
    let touchEndY = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartY = e.changedTouches[0].screenY;
    });

    document.addEventListener('touchend', function(e) {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        const swipeDistance = touchStartY - touchEndY;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                // Swipe up - could scroll to next section
                scrollToNextSection();
            } else {
                // Swipe down - could scroll to previous section
                scrollToPreviousSection();
            }
        }
    }

    function scrollToNextSection() {
        const sections = document.querySelectorAll('section');
        const currentScroll = window.pageYOffset;
        const windowHeight = window.innerHeight;

        for (let section of sections) {
            const sectionTop = section.offsetTop;
            if (sectionTop > currentScroll + windowHeight / 2) {
                window.scrollTo({
                    top: sectionTop - 80,
                    behavior: 'smooth'
                });
                break;
            }
        }
    }

    function scrollToPreviousSection() {
        const sections = Array.from(document.querySelectorAll('section')).reverse();
        const currentScroll = window.pageYOffset;

        for (let section of sections) {
            const sectionTop = section.offsetTop;
            if (sectionTop < currentScroll - 100) {
                window.scrollTo({
                    top: sectionTop - 80,
                    behavior: 'smooth'
                });
                break;
            }
        }
    }
});