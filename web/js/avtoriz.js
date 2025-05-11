document.addEventListener('DOMContentLoaded', function() {
      const dialog = document.getElementById('loginErrorDialog');
      if (dialog && dialog.classList.contains('show')) {
          setTimeout(function() {
              dialog.classList.remove('show');
          }, 1000); // 1 секунда
      }
  });