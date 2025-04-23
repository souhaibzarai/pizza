function addToCart(productId) {
  // Send AJAX request to add item to cart
  fetch('update_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: 1,
      action: 'update'
    })
  })
    .then(response => response.json())
    .then(data => {
      try {
        // Show notification
        showCartNotification('Produit ajouté au panier!');

        // Vérifier si data.cart existe avant de l'utiliser
        if (data && data.cart) {
          updateCartCount(data.cart);
        } else {
          console.log("Avertissement: données du panier non disponibles pour la mise à jour du compteur");
        }
      }
      catch (e) {
        console.error('Erreur lors de l\'ajout au panier:', e);
        alert('Erreur lors de l\'ajout au panier: ' + e.message);
      }
    })
    .catch(error => {
      console.error('Erreur de requête AJAX:', error);
      alert('Erreur de communication avec le serveur: ' + error.message);
    });
}

function showCartNotification(message) {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = 'cart-notification';

  const notificationContent = `
        <div class="notification-icon">
            <img src="icons/check.png" alt="Success">
        </div>
        <div class="notification-message">${message}</div>
    `;

  notification.innerHTML = notificationContent;
  document.body.appendChild(notification);

  // Show notification with animation
  setTimeout(() => {
    notification.classList.add('show');
  }, 10);

  // Hide after 3 seconds
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}

function updateCartCount(cart) {
  const cartCountElement = document.querySelector('.cart-count');
  if (cartCountElement) {
    const itemCount = Object.values(cart).reduce((sum, qty) => sum + parseInt(qty), 0);
    cartCountElement.textContent = itemCount;
    cartCountElement.style.display = itemCount > 0 ? 'flex' : 'none';
  }
}