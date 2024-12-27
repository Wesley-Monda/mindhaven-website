document.getElementById('login-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  const formData = new FormData(this);
  
  fetch('app.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.text())
  .then(data => {
      if (data.trim() === 'login_success') {
          document.getElementById('login-section').classList.remove('active');
          document.getElementById('booking-section').classList.add('active');
      } else {
          alert('Login failed: ' + data);
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
});

document.getElementById('register-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the default form submission

  const formData = new FormData(this);
  
  fetch('app.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.text())
  .then(data => {
      if (data.trim() === 'register_success') {
          document.getElementById('register-section').classList.remove('active');
          document.getElementById('booking-section').classList.add('active');
      } else {
          alert('Registration failed: ' + data);
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
});

document.getElementById('show-register').addEventListener('click', function() {
  document.getElementById('login-section').classList.remove('active');
  document.getElementById('register-section').classList.add('active');
});

document.getElementById('show-login').addEventListener('click', function() {
  document.getElementById('register-section').classList.remove('active');
  document.getElementById('login-section').classList.add('active');
});

document.getElementById('show-forgot-password').addEventListener('click', function() {
  document.getElementById('login-section').classList.remove('active');
  document.getElementById('forgot-password-section').classList.add('active');
});

document.getElementById('show-login-from-forgot').addEventListener('click', function() {
  document.getElementById('forgot-password-section').classList.remove('active');
  document.getElementById('login-section').classList.add('active');
});

document.getElementById('show-login-from-reset').addEventListener('click', function() {
  document.getElementById('reset-password-section').classList.remove('active');
  document.getElementById('login-section').classList.add('active');
});
