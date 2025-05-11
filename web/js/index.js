document.addEventListener('DOMContentLoaded', function() {
      const clickableItems = document.querySelectorAll('.clickable-item'); // Select all clickable items
  
      clickableItems.forEach(item => {
          item.addEventListener('click', function() {
              const href = this.getAttribute('data-href');
              if (href) {
                  window.location.href = href;
              }
          });
          item.style.cursor = 'pointer'; // Change cursor to pointer
      });
  });