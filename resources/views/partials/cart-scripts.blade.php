<script>
// showMessage replaced by toast for all notifications
function showMessage(message, type = 'success') {
  toast(type === 'danger' ? 'Network error' : message, type === 'danger' ? message : '', type === 'danger' ? 'error' : type);
}

// Use SweetAlert2 for toast notifications
function toast(title, message = '', icon = 'success') {
  Swal.fire({
    icon: icon,
    title: title,
    text: message,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  });
}

function setLoading(btn, loading) {
  if (!btn) return;
  if (loading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = 'Loading...';
  } else {
    btn.disabled = false;
    if (btn.dataset.originalText) btn.innerHTML = btn.dataset.originalText;
  }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

function addToCart(payload, btn) {
  setLoading(btn, true);
  fetch('/api/cart/add-to-cart', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    credentials: 'include', // Ensure cookies are sent
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Try to extract product name from DOM
      let productName = '';
      if (btn && btn.closest('.product-card')) {
        const nameElem = btn.closest('.product-card').querySelector('h6');
        if (nameElem) productName = nameElem.textContent.trim();
      }
      const toastTitle = productName ? `${productName} added to cart!` : (data.message || 'Product added to cart successfully');
      toast(toastTitle, '', 'success');
      
      // Update cart count in header if possible
       const cartCountElem = document.getElementById('cartCountValue');
       if (cartCountElem) {
           // Simple increment or re-fetch
           fetch('/api/cart/count', { credentials: 'include' })
            .then(r => r.json())
            .then(d => { if(d.status) cartCountElem.textContent = d.count; });
       }

    } else if (data.errors) {
      toast('Validation error', Object.values(data.errors).join(', '), 'error');
    } else {
      toast('Error', data.error || 'Failed to add to cart', 'error');
    }
  })
  .catch(() => showMessage('Network error', 'danger'))
  .catch(() => toast('Network error', '', 'error'))
  .finally(() => setLoading(btn, false));
}

function buyNow(payload, btn) {
  setLoading(btn, true);
  fetch('/api/cart/buy-now', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    credentials: 'include', // Ensure cookies are sent
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      toast('Purchase successful!', '', 'success');
      // Optionally redirect to checkout or order page
    } else if (data.errors) {
      toast('Validation error', Object.values(data.errors).join(', '), 'error');
    } else {
      toast('Error', data.error || 'Failed to buy now', 'error');
    }
  })
  .catch(() => showMessage('Network error', 'danger'))
  .catch(() => toast('Network error', '', 'error'))
  .finally(() => setLoading(btn, false));
}

$(document).on('click', '.pagination a', function(e) {
  var href = $(this).attr('href');
  if (href && href !== '#') {
    window.location.href = href;
  }
});
</script>
