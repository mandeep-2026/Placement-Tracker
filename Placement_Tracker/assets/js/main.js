// Floating shapes animation
document.addEventListener('DOMContentLoaded', function() {
    // Add floating shapes to body
    const shapesHTML = `
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    `;
    document.body.insertAdjacentHTML('afterbegin', shapesHTML);

    // Animate cards on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    });

    document.querySelectorAll('.card, .stats-card').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });

    // Button ripple effect
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255,255,255,0.98)';
            navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
        } else {
            navbar.style.background = 'rgba(255,255,255,0.95)';
            navbar.style.boxShadow = '0 8px 32px rgba(0,0,0,0.1)';
        }
    });

    // Status badges pulse animation
    setInterval(() => {
        document.querySelectorAll('.status-badge').forEach(badge => {
            badge.style.transform = 'scale(1.05)';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 200);
        });
    }, 3000);
});