
$('#login-form').on('submit', function(e) {
  e.preventDefault();
  const username = $('#username').val();
  const password = $('#password').val();
  $.ajax({
    url: '/api/login',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ username, password }),
    success: function(response) {
      if (response.token) {
        localStorage.setItem('token', response.token);
        window.location.href = '/index.html';
      } else {
        Swal.fire('Error', 'Credenciales incorrectas', 'error');
      }
    },
    error: function() {
      Swal.fire('Error', 'No se pudo iniciar sesión', 'error');
    }
  });
});
