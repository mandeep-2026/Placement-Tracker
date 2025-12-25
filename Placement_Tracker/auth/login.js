// auth/login.js - UPDATED & FULLY WORKING
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const toggleBtn = document.getElementById('togglePassword');
    const demoBtns = document.querySelectorAll('.demo-btn');

    // Password toggle 👁️
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const passwordInput = form.querySelector('input[name="password"]');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    // Demo login buttons 🚀
    demoBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const data = {
                admin: { email: 'adm1n@college.com', password: 'adm1n' },
                student: { email: 'student@college.com', password: '123456' }
            };

            // Fill correct email input 👉 IMPORTANT FIX
            form.querySelector('input[name="email"]').value = data[this.dataset.user].email;
            form.querySelector('input[name="password"]').value = data[this.dataset.user].password;

            // Select correct login role automatically 👇
            const roleSelect = form.querySelector('select[name="role"]');
            if (roleSelect) roleSelect.value = this.dataset.user;

            form.submit();
        });
    });

    // Enter key submit ⌨️
    const passwordInput = form.querySelector('input[name="password"]');
    if (passwordInput) {
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') form.submit();
        });
    }

    // Loading animation on submit ⏳
    form.addEventListener('submit', function() {
        const btn = this.querySelector('.btn-login');
        const span = btn.querySelector('span');
        if (span) {
            span.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
        }
        btn.disabled = true;
    });
});