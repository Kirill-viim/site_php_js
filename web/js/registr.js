document.addEventListener('DOMContentLoaded', function() {
      const dialog = document.getElementById('activationSentDialog');
      if (dialog && dialog.classList.contains('show')) {
          setTimeout(function() {
              dialog.classList.remove('show');
          }, 5000); // 5 seconds
      }
  });