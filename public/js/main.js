document.addEventListener('DOMContentLoaded', function () {
  const navToggle = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.querySelector('.navbar-collapse');

  if (navToggle && navbarCollapse) {
    navToggle.addEventListener('click', function () {
      navbarCollapse.classList.toggle('show');
    });
  }
});
