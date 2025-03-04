window.addEventListener('message', function(event) {
  if (!event.data || event.data.sender !== 'MAIKA') return; // Check sender

  showNotification(event.data);
});

function showNotification({ type, message, duration, closeMode, width }) {
  const notification = document.createElement('div');
  notification.textContent = message;
  notification.style.position = 'fixed';
  notification.style.top = '80px';
  notification.style.right = '0';
  notification.style.padding = '10px 12px 10px 20px';
  notification.style.color = 'white';
  notification.style.fontSize = '16px';
  notification.style.borderRadius = '5px';
  notification.style.width = width === 'auto' ? 'auto' : `${width}`;
  notification.style.textAlign = 'center';
  notification.style.display = 'flex';
  notification.style.alignItems = 'center';
  notification.style.justifyContent = 'space-between';
  notification.style.gap = '10px';
  notification.style.opacity = '0'; // Animation in-out
  notification.style.transition = 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out';
  notification.style.transform = 'translateX(20px)';

  // Type color
  const colors = {
      success: '#21a748',
      info: '#5581cf',
      warning: '#ffb800',
      error: '#ff393c',
  };
  notification.style.backgroundColor = colors[type] || 'gray';

  if (closeMode === 'manual') {
      const closeButton = document.createElement('button');
      closeButton.textContent = '✖';
      closeButton.style.background = 'transparent';
      closeButton.style.color = 'white';
      closeButton.style.border = 'none';
      closeButton.style.cursor = 'pointer';
      closeButton.style.fontSize = '16px';
      closeButton.addEventListener('click', () => {
          hideNotification(notification);
      });

      notification.appendChild(closeButton);
  }

  // Show
  document.body.appendChild(notification);

  // Trigger animation show in
  setTimeout(() => {
      notification.style.opacity = '1';
      notification.style.transform = 'translateX(0)';
  }, 50);

  if (closeMode === 'auto') {
      setTimeout(() => {
          hideNotification(notification);
      }, duration);
  }
}

function hideNotification(notification) {
  if (document.body.contains(notification)) {
      notification.style.opacity = '0';
      notification.style.transform = 'translateX(20px)';
      setTimeout(() => {
          if (notification.parentNode) {
              notification.parentNode.removeChild(notification);
          }
      }, 500);
  }
}