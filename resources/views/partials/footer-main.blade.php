<footer class="footer-container">
  <div class="footer-row">
    <ul class="payment-icons">

      <li><img src="{{ asset('assets/images/payments/visa.svg') }}" alt="Visa"></li>
      <li><img src="{{ asset('assets/images/payments/mastercard.svg') }}" alt="Mastercard"></li>
      <li><img src="{{ asset('assets/images/payments/paypal.svg') }}" alt="PayPal" class="paypal"></li>
      <li><img src="{{ asset('assets/images/payments/netbanking.svg') }}" alt="Net Banking"></li>
      <li><img src="{{ asset('assets/images/payments/american-express.svg') }}" alt="American Express"></li>
      <li><img src="{{ asset('assets/images/payments/rupay.png') }}" alt="RuPay"></li>
      <li><img src="{{ asset('assets/images/payments/bhim.svg') }}" alt="BHIM UPI"></li>

      <li class="custom-option">
        <a href="#">
          <img src="{{ asset('assets/images/payments/footer-icon1.svg') }}" alt="Cash on Delivery">
          <span>Cash on Delivery</span>
        </a>
      </li>

      <li class="custom-option">
        <a href="#">
          <img src="{{ asset('assets/images/payments/footer-icon2.svg') }}" alt="Lab Certified">
          <span>Lab Certified</span>
        </a>
      </li>

      <li class="custom-option">
        <a href="#">
          <img src="{{ asset('assets/images/payments/footer-icon3.svg') }}" alt="Easy Returns">
          <span>Easy Returns</span>
        </a>
      </li>

    </ul>
  </div>
</footer>




<!-- Footer -->

<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-col">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#">Customer Reviews</a></li>
        <li><a href="#">Our Blogs</a></li>
        <li><a href="#">Store Locator</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Gift Cards</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Info</h4>
      <ul>
        <li><a href="#">Shipping & Returns</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">International Shipping</a></li>
        <li><a href="#">FAQs & Support</a></li>
        <li><a href="#">Terms of Service</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Contact Us</h4>
      <p>BIS : HM/C - 6290031216</p>
      <p>Indiejewel Fashions Pvt Ltd</p>
      <p>Bangalore – 560062</p>
      <p><i class="fa fa-phone"></i> 9228837724</p>
      <p><a href="#">Raise a Ticket</a></p>
    </div>

    <div class="footer-col newsletter">
      <h4>Subscribe</h4>
      <p>Get exclusive offers & updates</p>
      <form>
        <input type="email" placeholder="Enter your email" required>
        <button type="submit">Subscribe</button>
      </form>

      <div class="social-icons">
        <a href="#">
          <img src="{{ asset('assets/images/fac-book.png') }}" alt="Facebook">
        </a>
        <a href="#">
          <img src="{{ asset('assets/images/instra.png') }}" alt="Instagram">
        </a>
        <a href="#">
          <img src="{{ asset('assets/images/youtub.png') }}" alt="YouTube">
        </a>
        <a href="#">
          <img src="{{ asset('assets/images/indin.png') }}" alt="LinkedIn">
        </a>
      </div>
    </div>
  </div>

  <!-- <div class="footer-middle">
    <div>
      <h4>Download Our App</h4>
      <p>(8M+ Downloads | 4.4⭐)</p>
      <img src="{{ asset('assets/images/payment.png') }}" alt="App Download">
    </div>

    <div>
      <h4>Channel Partners</h4>
      <div class="partners">
        <img src="assets/images/partners.png" alt="App Download">
          </div>
    </div>
  </div> -->

  <div class="footer-bottom">
     <div class="wr_p">
      <h4>Download Our App</h4>
      <img src="assets/images/payment.png" alt="App Download">
      </div>

    <p>© 2026 Your Brand Name. All Rights Reserved.</p>
    <div class="payments">
      <img src="{{ asset('assets/images/payment-2.png') }}" alt="App Download">
      <!-- <span>Visa</span>
      <span>Mastercard</span>
      <span>RuPay</span>
      <span>UPI</span> -->
    </div>
  </div>
</footer>








<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>




<script>
let cart = 0;

// Add to cart
// function addToCart(){
//     cart++;
//     document.getElementById("cartCount").innerText = cart;
// }

// Buy now
// function buyNow(product){
//     alert("Proceeding to checkout for: " + product);
// }

// Wishlist toggle
// document.querySelectorAll(".wishlist").forEach(icon=>{
//     icon.addEventListener("click",function(){
//         this.classList.toggle("bi-heart-fill");
//         this.classList.toggle("bi-heart");
//     });
// });
</script>

<script>
  document.querySelectorAll(".faq-question").forEach(button => {
    button.addEventListener("click", () => {
      const item = button.parentElement;
      item.classList.toggle("active");
    });
  });
</script>

<script>
  const openBtn = document.getElementById("openPincodePopup");
  const modal = document.getElementById("pincodeModal");
  const closeBtn = document.querySelector(".pincode-close");

  openBtn.addEventListener("click", () => {
    modal.classList.add("active");
  });

  closeBtn.addEventListener("click", () => {
    modal.classList.remove("active");
  });

  modal.addEventListener("click", e => {
    if (e.target === modal) {
      modal.classList.remove("active");
    }
  });
</script>

<script>
  // Toggle dropdowns
  document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.stopPropagation();
      closeAll();
      btn.nextElementSibling.style.display = "block";
    });
  });

  // Select option
  document.querySelectorAll(".filter-menu li").forEach(item => {
    item.addEventListener("click", () => {
      const dropdown = item.closest(".filter-dropdown");
      dropdown.querySelector(".filter-btn").textContent = item.textContent;
      closeAll();

      console.log("Filter applied:", item.textContent);
    });
  });

  // Close on outside click
  document.addEventListener("click", closeAll);

  function closeAll() {
    document.querySelectorAll(".filter-menu").forEach(menu => {
      menu.style.display = "none";
    });
  }

  // Sort change
  document.getElementById("sortSelect").addEventListener("change", e => {
    console.log("Sort by:", e.target.value);
  });
</script>

<script>
  document.querySelectorAll(".wishlist-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      btn.classList.toggle("active");
      btn.textContent = btn.classList.contains("active") ? "♥" : "♡";
    });
  });
</script>

<script>
  const pages = document.querySelectorAll(".page-item");
  let currentPage = 1;
  const totalPages = 4;

  function updatePagination(page) {
    currentPage = page;

    pages.forEach(item => item.classList.remove("active"));

    document
      .querySelector(`a[data-page="${page}"]`)
      ?.parentElement.classList.add("active");

    document.querySelector('[data-page="prev"]').parentElement
      .classList.toggle("disabled", page === 1);

    document.querySelector('[data-page="next"]').parentElement
      .classList.toggle("disabled", page === totalPages);

    console.log("Load products for page:", page);
    // 👉 Call AJAX / API / filter function here
  }

  document.querySelectorAll(".pagination a").forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const page = link.dataset.page;

      if (page === "prev" && currentPage > 1) {
        updatePagination(currentPage - 1);
      } else if (page === "next" && currentPage < totalPages) {
        updatePagination(currentPage + 1);
      } else if (!isNaN(page)) {
        updatePagination(Number(page));
      }
    });
  });
</script>

<script>
    // 1. Pincode/Delivery Checker
    function checkDelivery() {
        const pin = document.getElementById('pincode').value;
        const msg = document.getElementById('delivery-msg');
        
        if (pin.length === 6) {
            // Mock logic: Date = today + 3 days
            const date = new Date();
            date.setDate(date.getDate() + 3);
            const options = { weekday: 'long', day: 'numeric', month: 'short' };
            msg.innerHTML = `Expected delivery by <strong>${date.toLocaleDateString('en-US', options)}</strong>`;
            msg.style.display = 'block';
            msg.style.color = 'green';
        } else {
            msg.innerText = "Please enter a valid 6 digit pincode.";
            msg.style.display = 'block';
            msg.style.color = 'red';
        }
    }

    // 4. Toggle Offer Accordion
    function toggleOffer() {
        const content = document.getElementById('offer-content');
        const arrow = document.getElementById('offer-arrow');
        
        if (content.style.display === "block") {
            content.style.display = "none";
            arrow.innerHTML = "▼";
        } else {
            content.style.display = "block";
            arrow.innerHTML = "▲";
        }
    }
</script>

<script>
  function changeImage(el) {
    const mainImage = document.getElementById("mainImage");

    // Change main image
    mainImage.src = el.src;

    // Remove active class from all thumbs
    document.querySelectorAll(".thumb").forEach(thumb => {
      thumb.classList.remove("active");
    });

    // Add active class
    el.classList.add("active");
  }
</script>

<script>
$(document).ready(function(){
  $(".bestselling-carousel").owlCarousel({
    loop: true,
    margin: 16,
    nav: true,
    dots: false,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive: {
      0: {
        items: 1.2
      },
      480: {
        items: 1.5
      },
      768: {
        items: 4
      }
    }
  });
});
</script>

<script>
let qty = 1;
const price = 2599;

function qtyPlus() {
  qty++;
  document.getElementById("qty").innerText = qty;
  updateTotal();
}

function qtyMinus() {
  if (qty > 1) qty--;
  document.getElementById("qty").innerText = qty;
  updateTotal();
}

function updateTotal() {
  let subtotal = qty * price;
  if (document.getElementById("giftWrap").checked) {
    subtotal += 50;
  }
  document.getElementById("subtotal").innerText = subtotal;
  document.getElementById("total").innerText = subtotal;
}

function removeItem() {
  document.querySelector(".cart-item").remove();
  document.getElementById("total").innerText = 0;
}
</script>
<script>
// Accordion toggle
document.querySelectorAll('.accordion__intro').forEach(title=>{
  title.addEventListener('click',()=>{
    const content = title.nextElementSibling;
    content.style.display =
      content.style.display === 'flex' ? 'none' : 'flex';
  });
});

// Copy code
document.querySelectorAll('.copy-code').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const parent = btn.closest('.offer');
    const code = parent.dataset.code;
    navigator.clipboard.writeText(code);
    btn.style.display='none';
    parent.querySelector('.copied').style.display='block';
  });
});

// View more offers
document.getElementById('show-more').onclick=()=>{
  document.getElementById('rest-offers').style.display='block';
  document.getElementById('show-more').style.display='none';
  document.getElementById('hide-more').style.display='block';
};
document.getElementById('hide-more').onclick=()=>{
  document.getElementById('rest-offers').style.display='none';
  document.getElementById('show-more').style.display='block';
  document.getElementById('hide-more').style.display='none';
};

// Gift wrap
document.getElementById('gift').addEventListener('change',()=>{
  alert('Gift wrap option updated (+₹50 per item)');
});
</script>

<script>
let basePrice = 2599;
let giftPrice = 50;

function updateQty(change){
  let qty = document.getElementById('qty');
  let newQty = parseInt(qty.value) + change;
  if(newQty < 1) return;
  qty.value = newQty;
  calculateTotal();
}

function toggleGift(){
  calculateTotal();
}

function calculateTotal(){
  let qty = parseInt(document.getElementById('qty').value);
  let gift = document.getElementById('gift').checked;
  let total = basePrice * qty + (gift ? giftPrice : 0);
  document.getElementById('total').innerText = total;
}

function openPopup(){
  document.getElementById('popup').style.display='block';
}

function closePopup(){
  document.getElementById('popup').style.display='none';
}

function removeItem(){
  document.getElementById('cartItem').remove();
  closePopup();
}
</script>

<script>
function toggleCoupons(){
  document.getElementById("coupons").classList.toggle("hidden");
}
function toggleGift(){
  document.getElementById("gift").classList.toggle("hidden");
}
function fillCode(code){
  document.getElementById("discountCode").value = code;
}
function applyDiscount(){
  alert("Discount Applied: " + document.getElementById("discountCode").value);
}
</script>

<script>
let price = 2599;
let qty = 1;

function updateQty(val){
  qty = Math.max(1, qty + val);
  document.getElementById("qty").value = qty;
  calculate();
}

function calculate(){
  let subtotal = price * qty;
  let tax = Math.round(subtotal * 0.03);
  let total = subtotal + tax;

  document.getElementById("subtotal").innerText = subtotal;
  document.getElementById("tax").innerText = tax;
  document.getElementById("total").innerText = total;
}

calculate();
</script>

<script>
let subtotal = 2599;
let taxRate = 0.03;
let discount = 0;

function applyCoupon(){
  const code = document.getElementById("coupon").value;
  if(code === "SAVE20"){
    discount = 0.2;
    alert("Coupon Applied!");
  } else {
    discount = 0;
    alert("Invalid Coupon");
  }
  updateTotals();
}

function updateTotals(){
  let discounted = subtotal - (subtotal * discount);
  let tax = Math.round(discounted * taxRate);
  let total = discounted + tax;

  document.getElementById("subtotal").innerText = discounted;
  document.getElementById("tax").innerText = tax;
  document.getElementById("total").innerText = total;
}

updateTotals();

 </script> 

<script>
  function qtyPlus() {
    let qty = document.getElementById("qty");
    qty.value = parseInt(qty.value) + 1;
    updatePrice();
  }

  function qtyMinus() {
    let qty = document.getElementById("qty");
    if (parseInt(qty.value) > 1) {
      qty.value = parseInt(qty.value) - 1;
      updatePrice();
    }
  }

  function updatePrice() {
    const carat = document.getElementById("carat").value;
    const qty = document.getElementById("qty").value;

    // Example price logic
    const basePrice = 10000; // per carat
    const total = basePrice * carat * qty;

    console.log("Total Price:", total);
    // You can show this in UI if needed
  }
</script>

<!-- JS -->
<script>
  function showSection(id) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.add('d-none'));
    document.getElementById(id).classList.remove('d-none');

    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
    event.target.classList.add('active');
  }
</script>
<script>
function toggleCart() {
  document.getElementById("miniCart").classList.toggle("show");
}

function removeCartItem(btn) {
  btn.closest(".cart-item").remove();
  updateCart();
}

function updateCart() {
  const items = document.querySelectorAll(".cart-item").length;
  document.getElementById("cartCount").innerText = items;

  if(items === 0){
    document.getElementById("cartTotal").innerText = "₹0";
  }
}

// Close cart when clicking outside
document.addEventListener("click", function(e){
  const cart = document.getElementById("miniCart");
  const wrapper = document.getElementById("cartWrapper");
  if (!wrapper.contains(e.target)) {
    cart.classList.remove("show");
  }
});
</script>

<script>
function showLogin(){
  document.getElementById("loginForm").style.display = "block";
  document.getElementById("registerForm").style.display = "none";
}

function showRegister(){
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("registerForm").style.display = "block";
}

function showOTP(){
  document.getElementById("passwordLogin").style.display = "none";
  document.getElementById("otpLogin").style.display = "block";
}

function showPasswordLogin(){
  document.getElementById("passwordLogin").style.display = "block";
  document.getElementById("otpLogin").style.display = "none";
}

function sendOTP(){
  document.getElementById("otpBox").style.display = "block";
  alert("Demo OTP Sent: 1234");
}
</script>


<script>
const searchInput = document.getElementById("searchInput");
const suggestionsBox = document.getElementById("searchSuggestions");

const products = [
  "Silver Ring",
  "Gold Necklace",
  "Diamond Ring",
  "Rudraksha Mala",
  "Blue Sapphire Gemstone",
  "Emerald Stone",
  "Gold Bracelet",
  "Silver Earrings",
  "Ruby Gemstone",
  "Pearl Necklace",
  "Astrology Consultation",
  "Online Astrology Classes"
];

let currentFocus = -1;

searchInput.addEventListener("input", function() {
  const value = this.value.toLowerCase();
  suggestionsBox.innerHTML = "";
  currentFocus = -1;

  if (!value) {
    suggestionsBox.classList.add("d-none");
    return;
  }

  const filtered = products.filter(item =>
    item.toLowerCase().includes(value)
  );

  if (filtered.length === 0) {
    suggestionsBox.classList.add("d-none");
    return;
  }

  filtered.forEach(item => {
    const highlighted = item.replace(
      new RegExp(value, "gi"),
      match => `<span class="search-highlight">${match}</span>`
    );

    const div = document.createElement("div");
    div.classList.add("search-item");
    div.innerHTML = highlighted;

    div.addEventListener("click", function() {
      searchInput.value = item;
      suggestionsBox.classList.add("d-none");
    });

    suggestionsBox.appendChild(div);
  });

  suggestionsBox.classList.remove("d-none");
});

/* Keyboard Navigation */
searchInput.addEventListener("keydown", function(e) {
  const items = document.querySelectorAll(".search-item");

  if (e.key === "ArrowDown") {
    currentFocus++;
    addActive(items);
  } else if (e.key === "ArrowUp") {
    currentFocus--;
    addActive(items);
  } else if (e.key === "Enter") {
    e.preventDefault();
    if (currentFocus > -1 && items[currentFocus]) {
      items[currentFocus].click();
    }
  }
});

function addActive(items) {
  if (!items.length) return;
  removeActive(items);

  if (currentFocus >= items.length) currentFocus = 0;
  if (currentFocus < 0) currentFocus = items.length - 1;

  items[currentFocus].classList.add("active");
}

function removeActive(items) {
  items.forEach(item => item.classList.remove("active"));
}

/* Close when clicking outside */
document.addEventListener("click", function(e) {
  if (!searchInput.contains(e.target) && 
      !suggestionsBox.contains(e.target)) {
    suggestionsBox.classList.add("d-none");
  }
});
</script>




</body>
</html>
