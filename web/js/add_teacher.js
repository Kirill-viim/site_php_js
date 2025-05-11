// JavaScript для отображения и скрытия диалогового окна
document.addEventListener("DOMContentLoaded", function() {
      const errorDialog = document.getElementById('errorDialog');
      if (errorDialog.textContent.trim() !== '') {
          errorDialog.classList.add('show'); // Показать диалоговое окно
  
          setTimeout(function() {
              errorDialog.classList.remove('show'); // Скрыть диалоговое окно
              setTimeout(function() {
                  errorDialog.style.display = 'none'; // Полностью скрыть элемент
              }, 500); // Подождать окончание анимации
          }, 5000); // Скрыть через 5 секунд
      }
  });