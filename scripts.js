document.getElementById('show-register').addEventListener('click', function() {
    hideAllSections();
    document.getElementById('register-section').classList.remove('hidden');
});

document.getElementById('show-login').addEventListener('click', function() {
    hideAllSections();
    document.getElementById('login-section').classList.remove('hidden');
});

document.getElementById('show-forgot-password').addEventListener('click', function() {
    hideAllSections();
    document.getElementById('forgot-password-section').classList.remove('hidden');
});

document.getElementById('show-login-from-forgot').addEventListener('click', function() {
    hideAllSections();
    document.getElementById('login-section').classList.remove('hidden');
});

document.getElementById('show-login-from-reset').addEventListener('click', function() {
    hideAllSections();
    document.getElementById('login-section').classList.remove('hidden');
});

function hideAllSections() {
    document.getElementById('register-section').classList.add('hidden');
    document.getElementById('login-section').classList.add('hidden');
    document.getElementById('forgot-password-section').classList.add('hidden');
    document.getElementById('reset-password-section').classList.add('hidden');
    document.getElementById('booking-section').classList.add('hidden');
}
