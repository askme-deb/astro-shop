<script>
// showMessage replaced by toast for all notifications
function showMessage(message, type = 'success') {
  toast(type === 'danger' ? 'Network error' : message, type === 'danger' ? message : '', type === 'danger' ? 'error' : type);
}

// Custom toast notification (replaces SweetAlert2, but keeps the name 'toast')
function toast(title, messageOrIsError = '', type = 'success') {
  if (!title) return;

  let message = '';
  let variant = type;

  if (typeof messageOrIsError === 'boolean') {
    variant = messageOrIsError ? 'error' : 'success';
  } else if (typeof messageOrIsError === 'string') {
    message = messageOrIsError;
  }

  if (variant === 'danger') {
    variant = 'error';
  }

  let toast = document.getElementById('checkout-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'checkout-toast';
    toast.style.position = 'fixed';
    toast.style.left = '50%';
    toast.style.bottom = '24px';
    toast.style.transform = 'translateX(-50%)';
    toast.style.zIndex = '9999';
    toast.style.padding = '10px 16px';
    toast.style.borderRadius = '4px';
    toast.style.fontSize = '0.9rem';
    toast.style.color = '#fff';
    toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.25)';
    toast.style.maxWidth = '90%';
    toast.style.textAlign = 'center';
    toast.style.display = 'none';

    const titleEl = document.createElement('div');
    titleEl.id = 'checkout-toast-title';
    titleEl.style.fontWeight = '700';

    const messageEl = document.createElement('div');
    messageEl.id = 'checkout-toast-message';
    messageEl.style.marginTop = '4px';
    messageEl.style.fontSize = '0.85rem';

    toast.appendChild(titleEl);
    toast.appendChild(messageEl);
    document.body.appendChild(toast);
  }

  const titleEl = document.getElementById('checkout-toast-title');
  const messageEl = document.getElementById('checkout-toast-message');

  if (titleEl) {
    titleEl.textContent = title;
  }

  if (messageEl) {
    messageEl.textContent = message;
    messageEl.style.display = message ? 'block' : 'none';
  }

  toast.style.backgroundColor = variant === 'error' ? '#d32f2f' : '#2e7d32';
  toast.style.display = 'block';

  clearTimeout(toast._hideTimer);
  toast._hideTimer = setTimeout(function() {
    toast.style.display = 'none';
  }, 3000);
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

window.AstroShop = window.AstroShop || {};

window.AstroShop.escapeHtml = function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
};

window.AstroShop.extractApiMessage = function extractApiMessage(value, fallback) {
  const fallbackMessage = fallback || 'Unable to submit your review right now. Please try again.';

  if (typeof value !== 'string') {
    return fallbackMessage;
  }

  const trimmed = value.trim();

  if (!trimmed) {
    return fallbackMessage;
  }

  const jsonMatch = trimmed.match(/\{.*\}$/s);
  if (jsonMatch) {
    try {
      const decoded = JSON.parse(jsonMatch[0]);
      const decodedMessage = decoded && (decoded.message || decoded.error);

      if (typeof decodedMessage === 'string' && decodedMessage.trim()) {
        return decodedMessage.trim();
      }
    } catch (error) {
    }
  }

  return trimmed;
};

window.AstroShop.renderProductRatingSummary = function renderProductRatingSummary(product) {
  const rating = product && product.rating ? product.rating : '0.0';
  const reviewsCount = product && (product.reviews_count ?? product.total_reviews ?? product.review_count)
    ? (product.reviews_count ?? product.total_reviews ?? product.review_count)
    : '0';

  return `⭐ ${rating} | ${reviewsCount} Reviews`;
};

window.AstroShop.renderProductDetailRatingSummary = function renderProductDetailRatingSummary(rating, reviewsCount) {
  return `⭐ ${rating} | <span id="product-rating-count">${reviewsCount}</span> Reviews`;
};

window.AstroShop.renderProductCardRatingText = function renderProductCardRatingText(rating, reviewsCount) {
  const normalizedRating = Number(rating || 0).toFixed(1);
  const normalizedReviewsCount = Number(reviewsCount || 0);

  return `⭐ ${normalizedRating} | ${normalizedReviewsCount} Reviews`;
};

window.AstroShop.renderAverageStars = function renderAverageStars(rating) {
  const roundedRating = Math.round(Number(rating || 0));

  return Array.from({ length: 5 }, function(_, index) {
    return `<i class="bi ${index < roundedRating ? 'bi-star-fill' : 'bi-star'}"></i>`;
  }).join('');
};

window.AstroShop.calculateReviewBreakdown = function calculateReviewBreakdown(reviews) {
  const distribution = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };

  if (!Array.isArray(reviews)) {
    return distribution;
  }

  reviews.forEach(function(review) {
    const rating = Math.round(Number(review && review.rating ? review.rating : 0));
    if (distribution[rating] !== undefined) {
      distribution[rating] += 1;
    }
  });

  return distribution;
};

window.AstroShop.updateReviewBreakdown = function updateReviewBreakdown(distribution, totalReviews) {
  const safeDistribution = distribution || { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
  const total = Number(totalReviews || 0);

  [5, 4, 3, 2, 1].forEach(function(star) {
    const count = Number(safeDistribution[star] || 0);
    const fill = document.getElementById(`review-breakdown-fill-${star}`);
    const countEl = document.getElementById(`review-breakdown-count-${star}`);
    const percentage = total > 0 ? (count / total) * 100 : 0;

    if (fill) {
      fill.style.width = `${percentage}%`;
    }

    if (countEl) {
      countEl.textContent = count.toLocaleString('en-IN');
    }
  });
};

window.AstroShop.escapeHtmlAttribute = function escapeHtmlAttribute(value) {
  return window.AstroShop.escapeHtml(value ?? '');
};

window.AstroShop.formatReviewDate = function formatReviewDate(value) {
  if (!value) {
    return '';
  }

  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return window.AstroShop.escapeHtml(String(value));
  }

  const now = new Date();
  const diffMs = now.getTime() - date.getTime();
  const diffMinutes = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMs / 3600000);
  const diffDays = Math.floor(diffMs / 86400000);

  if (diffMinutes < 1) {
    return 'Just now';
  }

  if (diffMinutes < 60) {
    return `${diffMinutes} minute${diffMinutes === 1 ? '' : 's'} ago`;
  }

  if (diffHours < 24) {
    return `${diffHours} hour${diffHours === 1 ? '' : 's'} ago`;
  }

  if (diffDays < 30) {
    return `${diffDays} day${diffDays === 1 ? '' : 's'} ago`;
  }

  return new Intl.DateTimeFormat('en-IN', {
    month: 'short',
    day: 'numeric',
    year: now.getFullYear() === date.getFullYear() ? undefined : 'numeric'
  }).format(date);
};

window.AstroShop.renderReviewList = function renderReviewList(reviews) {
  if (!Array.isArray(reviews) || !reviews.length) {
    return `
      <div class="review-empty-state text-center">
        <div class="review-empty-icon"><i class="bi bi-chat-square-text"></i></div>
        <strong class="text-muted d-block mb-1">No reviews yet.</strong>
        <span class="text-muted small">Be the first customer to share your experience.</span>
      </div>
    `;
  }

  return reviews.map(function(review) {
    const reviewerName = window.AstroShop.escapeHtml(
      review.user_name || review.user?.name || review.customer_name || 'Verified Customer'
    );
    const title = window.AstroShop.escapeHtml(review.title || 'Customer Review');
    const comment = window.AstroShop.escapeHtml(review.comment || review.review || '');
    const createdAt = window.AstroShop.formatReviewDate(review.created_at || review.createdAt || '');
    const verifiedPurchase = Boolean(review.is_verified_purchase || review.verified_purchase);
    const ratingValue = Math.max(1, Math.min(5, Number(review.rating || 0)));
    const stars = Array.from({ length: 5 }, function(_, index) {
      return `<i class="bi ${index < ratingValue ? 'bi-star-fill' : 'bi-star'}"></i>`;
    }).join('');

    return `
      <article class="review-item review-entry ${verifiedPurchase ? 'review-item-verified' : ''}">
        <div class="review-entry-top">
          <div class="review-entry-meta">
            <div class="review-avatar">${reviewerName.charAt(0).toUpperCase()}</div>
            <div>
              <h6 class="mb-1">${title}</h6>
              <div class="small text-muted">${reviewerName}${createdAt ? ` • ${createdAt}` : ''}</div>
            </div>
          </div>
          <div class="review-stars text-warning" aria-label="${ratingValue} star review">${stars}</div>
        </div>
        ${verifiedPurchase ? '<div class="review-verified-tag">Verified purchase</div>' : ''}
        <p class="mb-0 text-muted review-comment-copy">${comment}</p>
      </article>
    `;
  }).join('');
};

window.AstroShop.loadProductReviews = function loadProductReviews(productId) {
  return fetch(`/api/v1/reviews?reviewable_type=product&reviewable_id=${encodeURIComponent(productId)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(function(res) {
      return res.json();
    })
    .then(function(data) {
      const reviewListEl = document.getElementById('product-review-list');

      if (!reviewListEl) {
        return data;
      }

      if (data && data.status) {
        const reviews = Array.isArray(data.data) ? data.data : [];
        reviewListEl.innerHTML = window.AstroShop.renderReviewList(reviews);
        window.AstroShop.updateReviewBreakdown(
          window.AstroShop.calculateReviewBreakdown(reviews),
          reviews.length
        );
      } else {
        reviewListEl.innerHTML = `
          <div class="review-empty-state text-center">
            <div class="review-empty-icon"><i class="bi bi-exclamation-circle"></i></div>
            <strong class="text-muted d-block mb-1">Unable to load reviews right now.</strong>
            <span class="text-muted small">${window.AstroShop.escapeHtml(data?.message || 'Please try again in a moment.')}</span>
          </div>
        `;
      }

      return data;
    })
    .catch(function() {
      const reviewListEl = document.getElementById('product-review-list');
      if (reviewListEl) {
        window.AstroShop.updateReviewBreakdown({ 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 }, 0);
        reviewListEl.innerHTML = `
          <div class="review-empty-state text-center">
            <div class="review-empty-icon"><i class="bi bi-wifi-off"></i></div>
            <strong class="text-muted d-block mb-1">Unable to load reviews right now.</strong>
            <span class="text-muted small">Please check your connection and try again.</span>
          </div>
        `;
      }
    });
};

window.AstroShop.updateProductCardRatings = function updateProductCardRatings(productId, summary) {
  const payload = summary && summary.data && typeof summary.data === 'object'
    ? summary.data
    : (summary || {});
  const ratingValue = payload.average_rating || payload.rating || 0;
  const reviewCount = payload.total_reviews || payload.reviews_count || payload.review_count || 0;

  document.querySelectorAll(`.rating[data-review-summary][data-product-id="${productId}"]`).forEach(function(element) {
    element.textContent = window.AstroShop.renderProductCardRatingText(ratingValue, reviewCount);
  });
};

window.AstroShop.syncProductCardRatings = function syncProductCardRatings(root) {
  const scope = root && root.querySelectorAll ? root : document;
  const ratingElements = Array.from(scope.querySelectorAll('.rating[data-review-summary][data-product-id]'));

  if (!ratingElements.length) {
    return;
  }

  const productIds = [...new Set(ratingElements
    .map(function(element) {
      return element.getAttribute('data-product-id');
    })
    .filter(function(productId) {
      return productId && productId !== '0';
    }))];

  productIds.forEach(function(productId) {
    fetch(`/api/v1/reviews/summary?reviewable_type=product&reviewable_id=${encodeURIComponent(productId)}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function(res) {
        return res.json();
      })
      .then(function(data) {
        if (data && data.status) {
          window.AstroShop.updateProductCardRatings(productId, data);
        }
      })
      .catch(function() {
      });
  });
};

window.AstroShop.updateProductReviewSummary = function updateProductReviewSummary(summary) {
  const payload = summary && summary.data && typeof summary.data === 'object'
    ? summary.data
    : (summary || {});
  const ratingValue = Number(payload.average_rating || payload.rating || 0).toFixed(1);
  const reviewCount = Number(payload.total_reviews || payload.reviews_count || 0);
  const productRatingEl = document.getElementById('product-rating-summary');
  const productRatingCountEl = document.getElementById('product-rating-count');
  const ratingEl = document.getElementById('review-summary-rating');
  const countEl = document.getElementById('review-summary-count');
  const totalRatingsEl = document.getElementById('review-summary-total-ratings');
  const starStripEl = document.getElementById('review-summary-stars');
  const tabCountEl = document.getElementById('reviews-tab-count');

  if (productRatingEl) {
    productRatingEl.innerHTML = window.AstroShop.renderProductDetailRatingSummary(ratingValue, reviewCount);
  } else if (productRatingCountEl) {
    productRatingCountEl.textContent = reviewCount;
  }

  if (ratingEl) {
    ratingEl.innerHTML = `<i class="bi bi-star-fill"></i> ${ratingValue}`;
  }

  if (starStripEl) {
    starStripEl.innerHTML = window.AstroShop.renderAverageStars(ratingValue);
  }

  if (countEl) {
    countEl.textContent = `${reviewCount.toLocaleString('en-IN')} reviews`;
  }

  if (totalRatingsEl) {
    totalRatingsEl.textContent = `${reviewCount.toLocaleString('en-IN')} ratings`;
  }

  if (tabCountEl) {
    tabCountEl.textContent = reviewCount;
  }
};

window.AstroShop.loadProductReviewSummary = function loadProductReviewSummary(productId) {
  return fetch(`/api/v1/reviews/summary?reviewable_type=product&reviewable_id=${encodeURIComponent(productId)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(res => res.json())
    .then(data => {
      if (data && data.status) {
        window.AstroShop.updateProductReviewSummary(data);
      }

      return data;
    });
};

window.AstroShop.initProductReviewForm = function initProductReviewForm(config) {
  const settings = config || {};
  const productId = settings.productId || 0;
  const stars = document.querySelectorAll('.star-rating .star');
  const ratingInput = document.getElementById('review-rating');
  const submitBtn = document.getElementById('submit-review-btn');
  const errorDiv = document.getElementById('review-error');
  const successDiv = document.getElementById('review-success');
  const titleInput = document.getElementById('review-title');
  const commentInput = document.getElementById('review-comment');

  if (!productId || !ratingInput || !submitBtn || !errorDiv || !successDiv || !titleInput || !commentInput) {
    return;
  }

  let currentRating = Number(ratingInput.value || 0);

  const paintStars = function paintStars(activeRating) {
    stars.forEach((star, index) => {
      star.style.color = index < activeRating ? '#ffc107' : '#ddd';
    });
  };

  stars.forEach((star, index) => {
    star.addEventListener('mouseenter', function() {
      paintStars(index + 1);
    });

    star.addEventListener('mouseleave', function() {
      paintStars(currentRating);
    });

    star.addEventListener('click', function() {
      currentRating = index + 1;
      ratingInput.value = currentRating;
      paintStars(currentRating);
    });
  });

  paintStars(currentRating);

  submitBtn.addEventListener('click', function() {
    errorDiv.classList.add('d-none');
    successDiv.classList.add('d-none');
    errorDiv.textContent = '';
    successDiv.textContent = '';

    const rating = parseInt(ratingInput.value, 10);
    const title = titleInput.value.trim();
    const comment = commentInput.value.trim();

    if (!rating || rating < 1 || rating > 5) {
      toast('Review not submitted', 'Please select a rating.', 'error');
      return;
    }

    if (title.length < 5) {
      toast('Review not submitted', 'Title must be at least 5 characters.', 'error');
      return;
    }

    if (comment.length < 10) {
      toast('Review not submitted', 'Comment must be at least 10 characters.', 'error');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';

    fetch('/api/v1/reviews', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      body: JSON.stringify({
        reviewable_type: 'product',
        reviewable_id: productId,
        rating: rating,
        title: title,
        comment: comment
      })
    })
      .then(res => res.json())
      .then(data => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Review';

        if (!data.status) {
          let msg = window.AstroShop.extractApiMessage(data.message || data.error || '', 'Unable to submit your review right now. Please try again.');
          if (data.reason) {
            const reasonMessages = {
              already_reviewed: 'You have already reviewed this item.',
              purchase_required: 'You need to complete a purchase before reviewing this item.',
              not_purchased: 'You need to complete a purchase before reviewing this item.',
              not_eligible: 'You need to complete a purchase before reviewing this item.',
              unauthenticated: 'Please sign in to submit a review.',
              unauthorized: 'Please sign in to submit a review.',
              not_found: 'The item you are trying to review could not be found.',
              reviewable_not_found: 'The item you are trying to review could not be found.'
            };

            if (!data.message && reasonMessages[data.reason]) {
              msg = reasonMessages[data.reason];
            }
          }
          toast('Review not submitted', msg, 'error');
          return;
        }

        toast(
          'Review submitted',
          window.AstroShop.extractApiMessage(data.message || '', 'Review submitted successfully and is pending approval.'),
          'success'
        );
        titleInput.value = '';
        commentInput.value = '';
        ratingInput.value = 0;
        currentRating = 0;
        paintStars(currentRating);
        window.AstroShop.loadProductReviewSummary(productId).catch(function() {});
        window.AstroShop.loadProductReviews(productId).catch(function() {});
      })
      .catch(function() {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Review';
        toast('Review not submitted', 'Network error. Please try again.', 'error');
      });
  });

  window.AstroShop.loadProductReviewSummary(productId).catch(function() {});
  window.AstroShop.loadProductReviews(productId).catch(function() {});
};

window.AstroShop.syncStoredPincode = function syncStoredPincode(inputId) {
  const input = document.getElementById(inputId || 'pincode');
  if (!input) {
    return;
  }

  try {
    const savedPincode = window.localStorage.getItem('delivery_pincode');
    if (savedPincode) {
      input.value = savedPincode;
    }
  } catch (error) {
  }
};

window.AstroShop.qtyMinus = function qtyMinus(inputId) {
  const qtyInput = document.getElementById(inputId || 'qty');
  if (!qtyInput) {
    return;
  }

  const value = parseInt(qtyInput.value, 10);
  if (value > 1) {
    qtyInput.value = value - 1;
  }
};

window.AstroShop.qtyPlus = function qtyPlus(inputId) {
  const qtyInput = document.getElementById(inputId || 'qty');
  if (!qtyInput) {
    return;
  }

  const value = parseInt(qtyInput.value, 10);
  qtyInput.value = value + 1;
};

window.AstroShop.checkDelivery = function checkDelivery(inputId, messageId) {
  const pincodeInput = document.getElementById(inputId || 'pincode');
  const msgDiv = document.getElementById(messageId || 'delivery-msg');

  if (!pincodeInput || !msgDiv) {
    return;
  }

  msgDiv.innerHTML = 'Checking...';

  fetch('/api/check-delivery', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Pincode': pincodeInput.value
    },
    body: JSON.stringify({ pincode: pincodeInput.value })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        msgDiv.innerHTML = `<span class="text-success">${window.AstroShop.escapeHtml(data.message)}</span>`;
      } else {
        msgDiv.innerHTML = `<span class="text-danger">${window.AstroShop.escapeHtml(data.message || 'Delivery not available.')}</span>`;
      }
    })
    .catch(function() {
      msgDiv.innerHTML = '<span class="text-danger">Error checking delivery.</span>';
    });
};

window.qtyMinus = function qtyMinusGlobal() {
  window.AstroShop.qtyMinus('qty');
};

window.qtyPlus = function qtyPlusGlobal() {
  window.AstroShop.qtyPlus('qty');
};

window.checkDelivery = function checkDeliveryGlobal() {
  window.AstroShop.checkDelivery('pincode', 'delivery-msg');
};

window.AstroShop.renderProductCard = function renderProductCard(product) {
  const safeProduct = product || {};
  const productId = safeProduct.id || 0;
  const hasSlug = typeof safeProduct.slug === 'string' && safeProduct.slug !== '';
  const productUrl = hasSlug ? `/products/${encodeURIComponent(safeProduct.slug)}` : '#';
  const productPayload = JSON.stringify({ product_id: productId, quantity: 1 });
  const imageUrl = window.AstroShop.escapeHtml(safeProduct.image_url || '/assets/images/product-1.jpg');
  const productName = window.AstroShop.escapeHtml(safeProduct.name || 'Product');
  const isInWishlist = Boolean(safeProduct.is_in_wishlist || safeProduct.in_wishlist);
  const wishlistIcon = isInWishlist ? 'bi-heart-fill' : 'bi-heart';
  const basePrice = safeProduct.product_price || safeProduct.final_price || safeProduct.price || '0';
  const sku = window.AstroShop.escapeHtml(safeProduct.sku || '');
  const originName = window.AstroShop.escapeHtml(safeProduct.origin_name || '');

  return `
    <div class="col-md-3 col-sm-6">
      <div class="product-card" data-product-id="${productId}">
        <i class="bi ${wishlistIcon} wishlist" data-product-id="${productId}"></i>
        <a href="${productUrl}">
          <img src="${imageUrl}" alt="${productName}">
        </a>
        <div class="rating" data-review-summary data-product-id="${productId}">${window.AstroShop.renderProductRatingSummary(safeProduct)}</div>
        <h6 class="mt-2">${productName}</h6>
        <div class="price-box mb-3">
          ${(safeProduct.discount_price && safeProduct.discount_price > 0)
            ? `<span class="price fs-3 fw-bold">₹${((safeProduct.product_price || 0) - (safeProduct.discount_price || 0)).toFixed(2)}</span>
               <span class="old-price ms-2">₹${window.AstroShop.escapeHtml(safeProduct.product_price || '')}</span>
               <span class="badge bg-success ms-2">${window.AstroShop.escapeHtml(safeProduct.discount_rate || '0')}% OFF</span>`
            : `<span class="price fs-3 fw-bold">₹${window.AstroShop.escapeHtml(basePrice)}</span><div class="discount-empty">&nbsp;</div>`
          }
        </div>
        ${sku ? `<div class="small text-muted mb-1">SKU: ${sku}</div>` : ''}
        ${originName ? `<div class="small text-muted mb-3">Origin: ${originName}</div>` : ''}
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick='addToCart(${productPayload}, this)'>Add to Cart</button>
          <button class="btn btn-buy" onclick='buyNow(${productPayload}, this)'>Buy Now</button>
        </div>
      </div>
    </div>
  `;
};

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
    credentials: 'include',
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Redirect to checkout with buyNow params
      const productId = payload.product_id || (payload.product && payload.product.id);
      const quantity = payload.quantity || 1;
      if (productId) {
        window.location.href = `/checkout?buyNow=1&product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`;
      } else {
        window.location.href = '/checkout';
      }
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

// Simple in-memory lock to prevent rapid double clicks on the same wishlist icon
const wishlistLocks = new WeakMap();

function syncWishlistHeartsOnLoad() {
  const icons = document.querySelectorAll('.wishlist[data-product-id]');
  if (!icons.length) return;

  const seen = new Set();

  icons.forEach(icon => {
    const id = icon.getAttribute('data-product-id');
    if (!id || seen.has(id)) return;
    seen.add(id);

    fetch('/api/wishlist/check', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
      },
      credentials: 'include',
      body: JSON.stringify({ product_id: parseInt(id, 10) })
    })
      .then(res => res.json())
      .then(data => {
        if (!data.status || typeof data.in_wishlist === 'undefined' || data.in_wishlist === null) {
          return;
        }

        const inWishlist = !!data.in_wishlist;

        document.querySelectorAll('.wishlist[data-product-id="' + id + '"]').forEach(el => {
          el.classList.toggle('bi-heart-fill', inWishlist);
          el.classList.toggle('bi-heart', !inWishlist);
        });
      })
      .catch(() => {
        // Fail silently; hearts will remain in their default state
      });
  });
}

function toggleWishlist(productId, iconEl) {
  if (!productId) return;

  const el = iconEl || null;

  if (el && wishlistLocks.get(el)) {
    return; // prevent spamming
  }

  if (el) {
    wishlistLocks.set(el, true);
    el.classList.add('wishlist-loading');
  }

  fetch('/api/wishlist/toggle', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    credentials: 'include',
    body: JSON.stringify({ product_id: productId })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const backendMessage = typeof data.message === 'string' ? data.message : '';

        // Backend now always returns authoritative in_wishlist via check endpoint
        const inWishlist = (typeof data.in_wishlist !== 'undefined' && data.in_wishlist !== null)
          ? !!data.in_wishlist
          : (el ? !el.classList.contains('bi-heart-fill') : true);

        if (el) {
          el.classList.toggle('bi-heart-fill', inWishlist);
          el.classList.toggle('bi-heart', !inWishlist);
        }

        // Use API message dynamically when available, with sensible fallback
        const title = backendMessage || (inWishlist
          ? 'Product added to wishlist!'
          : 'Removed from wishlist');

        toast(title, '', 'success');
      } else {
        const message = data.message || data.error || 'Failed to update wishlist';
        toast('Error', message, 'error');
      }
    })
    .catch(() => {
      toast('Network error', '', 'error');
    })
    .finally(() => {
      if (el) {
        el.classList.remove('wishlist-loading');
        wishlistLocks.delete(el);
      }
      // Refresh wishlist count in header if present
      if (typeof updateWishlistCount === 'function') {
        updateWishlistCount();
      }
    });
}

// Ensure wishlist hearts reflect server state on initial load/refresh
document.addEventListener('DOMContentLoaded', function () {
 // syncWishlistHeartsOnLoad();
  window.AstroShop.syncProductCardRatings(document);
  //alert('Welcome to Astro Shop! Explore our wide range of products and enjoy a seamless shopping experience.'); // Example alert on page load
});

$(document).on('click', '.pagination a', function(e) {
  var href = $(this).attr('href');
  if (href && href !== '#') {
    window.location.href = href;
  }
});

// Delegate click handling for wishlist icons
document.addEventListener('click', function (event) {
  const target = event.target;

  if (!target) return;

  const icon = target.closest('.wishlist');
  if (!icon) return;

  const productId = icon.getAttribute('data-product-id');
  if (!productId) return;

  event.preventDefault();
  toggleWishlist(parseInt(productId, 10), icon);
});




</script>
