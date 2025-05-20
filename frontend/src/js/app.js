
function getToken() {
  return localStorage.getItem('token');
}

function setToken(token) {
  localStorage.setItem('token', token);
}

function removeToken() {
  localStorage.removeItem('token');
}

function isAuthenticated() {
  return !!getToken();
}
if (!isAuthenticated() && !window.location.pathname.endsWith('login.html')) {
  Swal.fire({
    icon: 'info',
    title: 'Sesión requerida',
    text: 'Por favor inicia sesión para continuar',
    timer: 2000,
    showConfirmButton: false
  }).then(() => {
    window.location.href = '/login.html';
  });
}

window.logout = function () {
  removeToken();
  Swal.fire({
    icon: 'success',
    title: 'Sesión cerrada',
    text: 'Has cerrado sesión correctamente',
    timer: 1500,
    showConfirmButton: false
  }).then(() => {
    window.location.href = '/login.html';
  });
}
