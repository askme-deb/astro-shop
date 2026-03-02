@extends('layouts.app')

@section('title', 'Luxury Jewellery Store')

@section('content')


<!-- Hero Section -->
<!-- Hero Slider -->
<div id="heroSlider" class="carousel slide hero-slider" data-bs-ride="carousel" style="z-index: -1;">

  <div class="carousel-inner">

    <!-- Slide 1 -->
    <div class="carousel-item active"
      style="background-image: url('{{ asset('assets/images/product_1.jpeg') }}');">
      <div class="hero-content">
        <h1>Fresh Drop</h1>
        <p>Consider this your <strong>new silver update</strong></p>
        <button class="hero-btn">SHOP NOW</button>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item"
      style="background-image: url('{{ asset('assets/images/product_2.jpeg') }}');">
      <div class="hero-content">
        <h1>New Arrivals</h1>
        <p>Everyday elegance in silver</p>
        <button class="hero-btn">EXPLORE</button>
      </div>
    </div>

  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button"
    data-bs-target="#heroSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button"
    data-bs-target="#heroSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>


<!-- Categories -->
<section class="catagory_warp">
  <div class="container py-5">

    <!-- Heading -->
    <div class="text-center section-title mb-5">
      <h2>Buy Gemstones Online</h2>
      <p class="fw-semibold">PRODUCTS OF TRUSTED EXCELLENCE</p>
    </div>

    <!-- Gemstone Grid -->
    <div class="row g-4 d-md-flex">

      @forelse(($gemstoneCategories ?? []) as $category)
        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="{{ $category['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $category['name'] ?? 'Gemstone' }}">
            <h5>{{ $category['name'] ?? 'Gemstone' }} ›</h5>
            @if(!empty($category['description']))
              <p>{!! nl2br(e($category['description'])) !!}</p>
            @endif
          </div>
        </div>
      @empty
        <!-- Fallback static items if no categories are available -->
        {{-- <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Yellow Sapphire">
            <h5>YELLOW SAPPHIRE ›</h5>
            <p>Divine Luck, Prosperity,<br>Blissful Matrimony</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Blue Sapphire">
            <h5>BLUE SAPPHIRE ›</h5>
            <p>Great Fame, Discipline,<br>Reverses Misfortunes</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Emerald">
            <h5>EMERALD ›</h5>
            <p>Vocal Charm, Creativity,<br>Success in Business</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Ruby">
            <h5>RUBY ›</h5>
            <p>Great Health, Will Power,<br>Fame & Reputation</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Opal">
            <h5>OPAL ›</h5>
            <p>Luxury, Physical Beauty,<br>Romantic Bliss</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Pearl">
            <h5>PEARL ›</h5>
            <p>Mental Strength, Fortune,<br>Peace & Fulfillment</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Red Coral">
            <h5>RED CORAL ›</h5>
            <p>Averts Mishaps, Courage,<br>Overall Strength</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gem-card">
            <img src="images/001.png" alt="Hessonite">
            <h5>HESSONITE ›</h5>
            <p>Pacifies Rahu, Popularity,<br>Speculative Success</p>
          </div>
        </div> --}}
      @endforelse

    </div>

    <!-- Mobile Slider -->
  </div>

</section>

<!-- Products -->
<div class="container my-5 product_warp">
  <div class="row g-4 d-md-flex">

    <!-- Heading -->
    <div class="text-center section-title">
      <h2>Our Bestselling</h2>
      <!-- <p class="fw-semibold">PRODUCTS OF TRUSTED EXCELLENCE</p> -->
    </div>
    @forelse($bestSellingProducts ?? [] as $product)
    <div class="col-md-3 col-sm-6">
      <div class="product-card">
        <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
        <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
        <div class="rating">⭐ {{ $product['rating'] ?? '4.5' }}</div>
        <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
        <div>
          <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
          @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
          <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
          @endif
        </div>
        @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
        <div class="offer">Save {{ $product['discount_rate'] }}%</div>
        @else
        <div class="offer">&nbsp;</div>
        @endif
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
        </div>
      </div>
    </div>
    @empty
    <p>No bestselling products found.</p>
    @endforelse

  </div>

  <!-- Mobile Product Slider -->
  <div class="d-md-none">

    <div id="productSlider" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        @foreach(($bestSellingProducts ?? []) as $product)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
          <div class="product-card mx-auto">
            <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
            <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
            <div class="rating">⭐ {{ $product['rating'] ?? '4.5' }}</div>
            <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
            <div>
              <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
              @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
              <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
              @endif
            </div>
            @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
            <div class="offer">Save {{ $product['discount_rate'] }}%</div>
            @else
            <div class="offer">&nbsp;</div>
            @endif
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
              <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button"
        data-bs-target="#productSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>

      <button class="carousel-control-next" type="button"
        data-bs-target="#productSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>

  <div class="aa_l"><a href="{{ route('products.index') }}">View All</a></div>
</div>


<section class="sale-banner-wrapper">
  <div class="container">
    <div class="sale-banner">
      <div class="banner-content text-center text-md-start">
        <span class="sale-tag">LIMITED TIME OFFER</span>
        <h2>Flat <strong>15% OFF</strong><br>
          On All Products
        </h2>
        <p class="mb-4">Premium quality gemstones & jewellery at exclusive prices.</p>
        <a href="https://ecommerce.astrorajumaharaj.com/products" class="btn btn-dark px-4">
          Shop Now
        </a>
      </div>
    </div>
  </div>
</section>


<!-- Products: Category-wise Gemstones -->
<div class="container product_warp">
  <div class="row g-4 d-md-flex">

    <!-- Heading -->
    <div class="text-center section-title">
      <h2>Gemstones</h2>
      <!-- <p class="fw-semibold">PRODUCTS OF TRUSTED EXCELLENCE</p> -->
    </div>

    @forelse(($gemstoneCategoryProducts ?? []) as $product)
   <div class="col-md-3 col-sm-6">
      <div class="product-card">
      <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
        <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
        <div class="rating">⭐ {{ $product['rating'] ?? '4.5' }}</div>
        <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
        <div>
          <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
          @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
          <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
          @endif
        </div>
        @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
        <div class="offer">Save {{ $product['discount_rate'] }}%</div>
        @else
        <div class="offer">&nbsp;</div>
        @endif
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
        </div>
      </div>
    </div>
    @empty
      <p>No gemstones found for this category.</p>
    @endforelse

  </div>


  <div class="aa_l"><a href="{{ route('products.index') }}">View All</a></div>

</div>


<section class="gem-recommendation-wrap">
  <div class="container">

    <div class="gem-recommendation-grid">

      <!-- Left Block -->
      <div class="gem-recommendation-item">
        <div class="gem-recommendation-inner">
          <a href="#">
            <img
              src="{{ asset('assets/images/puja.png') }}"
              alt="Pooja Energization">
          </a>

          <div class="gem-recommendation-text">
            <h3>Pooja Energization</h3>
            <a href="#">
              Read More
            </a>
          </div>
        </div>
      </div>

      <!-- Right Block -->
      <div class="gem-recommendation-item">
        <div class="gem-recommendation-inner">
          <a href="#">
            <img
              src="{{ asset('assets/images/gems.png') }}"
              alt="Gemstone Recommendation">
          </a>

          <div class="gem-recommendation-text">
            <h3>Gemstone Recommendation</h3>
            <a href="#">
              Get Recommendation
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>


<section class="book-session-wrap">
  <div class="container">
    <div class="book-session-box">

      <div class="book-session-content">
        <h2>Book a Session</h2>
        <p>
          Get personalized guidance from our expert astrologers and gemstone
          consultants. Book your one-on-one session today.
        </p>

        <a href="#book-session" class="book-session-btn">
          Book a Session
        </a>
      </div>

    </div>
  </div>
</section>


<!-- Products: Featured -->
<div class="container product_warp">
  <div class="row g-4 d-md-flex">

    <!-- Heading -->
    <div class="text-center section-title">
      <h2>Our Featured Products</h2>
      <!-- <p class="fw-semibold">PRODUCTS OF TRUSTED EXCELLENCE</p> -->
    </div>

    @forelse(($featuredProducts ?? []) as $product)
      <div class="col-md-3 col-sm-6">
        <div class="product-card">
          <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
          <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
          <div class="rating">⭐ {{ $product['rating'] ?? '4.5' }}</div>
          <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
          <div>
            <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
            @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
              <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
            @endif
          </div>
          @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
            <div class="offer">Save {{ $product['discount_rate'] }}%</div>
          @else
            <div class="offer">&nbsp;</div>
          @endif
          <div class="d-grid gap-2 mt-3">
            <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
            <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
          </div>
        </div>
      </div>
    @empty
      <p>No featured products found.</p>
    @endforelse

  </div>


  <div class="aa_l"><a href="{{ route('products.index') }}">View All</a></div>

</div>



<section class="customer-stories">
  <div class="container">

    <!-- Section Title -->
    <div class="section-title">
      <h2>Customer Stories</h2>
    </div>

    <!-- Testimonials -->
    <div class="testimonial-slider">

      <!-- Testimonial Item -->
      <a href="/products/silver-purple-vibrant-ring" class="testimonial-card">
        <div class="testimonial-content">
          <h3>Virda</h3>
          <p>
            A big shout out to you guys for improving my hubby’s gifting
            tastes. Completely in love with my ring!
          </p>
        </div>
        <div class="testimonial-image">
          <img
            src="https://www.giva.co/cdn/shop/files/R0688_5-min.jpg?v=1711628600&width=320"
            alt="Silver Purple Vibrant Ring">
        </div>
      </a>

      <!-- Testimonial Item -->
      <a href="/products/silver-deer-heart-pendant" class="testimonial-card">
        <div class="testimonial-content">
          <h3>Harshika</h3>
          <p>
            Never thought buying jewellery would be this easy, thanks for
            helping make my mom’s birthday special.
          </p>
        </div>
        <div class="testimonial-image">
          <img
            src="https://www.giva.co/cdn/shop/files/PD096_5-min_9c05bf40-261f-4b5b-866d-9f70ee7d2e01.jpg?v=1699533271&width=320"
            alt="Silver Deer Heart Pendant">
        </div>
      </a>

      <!-- Testimonial Item -->
      <a href="/products/rose-gold-princess-earring" class="testimonial-card">
        <div class="testimonial-content">
          <h3>Priya</h3>
          <p>
            Gifted these earrings to my sister on her wedding and she loved them!
            I am obsessed with buying gifts from GIVA.
          </p>
        </div>
        <div class="testimonial-image">
          <img
            src="https://www.giva.co/cdn/shop/files/ER053_5-min_1259500a-8a3d-4baa-aed9-b7c1797b31a5.jpg?v=1699519485&width=320"
            alt="Rose Gold Princess Earring">
        </div>
      </a>

    </div>
  </div>
</section>


<section class="certificate-wrap">
  <div class="certificate-container">

    <!-- Left Image -->
    <div class="certificate-left">
      <a href="#">
        <img src="https://imgcdn1.gempundit.com/media/wysiwyg/grsss-bg-img-040324.webp"
          alt="Certified Gemstones">
      </a>
    </div>

    <!-- Right Content -->
    <div class="certificate-right">
      <h2>Certified Stones</h2>

      <ul class="certificate-logos">
        <li><a href="#"><img src="https://imgcdn1.gempundit.com/media/wysiwyg/gjepc-icon.webp" alt="GJEPC"></a></li>
        <li><a href="#"><img src="https://imgcdn1.gempundit.com/media/wysiwyg/affiliate-icon02-2.webp" alt="IGI"></a></li>
        <li><a href="#"><img src="https://imgcdn1.gempundit.com/media/wysiwyg/logo-home-grs-logo-040324.webp" alt="GRS"></a></li>
        <li><a href="#"><img src="https://imgcdn1.gempundit.com/media/wysiwyg/GIA-logo-logo-040324.webp" alt="GIA"></a></li>
      </ul>

      <div class="certificate-links">
        <a href="#" class="read-more">READ MORE</a>
        <a href="#" class="shop-now">SHOP NOW</a>
      </div>
    </div>

  </div>
</section>


<section class="faq-section">
  <div class="container">

    <div class="faq-header">
      <h2>Frequently Asked Questions</h2>
    </div>

    <div class="faq-accordion">

      <!-- FAQ Item -->
      <div class="faq-item active">
        <button class="faq-question">
          What are Gemstones?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>
            Gemstones are basically rocks or minerals that are cut and polished
            to enhance their natural beauty. They are of various colors and
            shapes. Today, gemstones are used for astronomical as well as
            ornamental purposes. Their cut, shine, and shape attract people
            from all over the world.
          </p>
        </div>
      </div>

      <!-- FAQ Item -->
      <div class="faq-item">
        <button class="faq-question">
          What is the history of gemstones?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>
            Gemstones are mineral crystals with dazzling brilliance and unique
            shapes. Found centuries ago, they were worn by Kings and Queens as
            talismans for protection and healing. Diamond, Ruby, Sapphire, and
            Emerald are classified as precious stones, while others are
            semi-precious based on rarity and quality.
          </p>
        </div>
      </div>

      <!-- FAQ Item -->
      <div class="faq-item">
        <button class="faq-question">
          Why should you wear gemstones?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>
            Gemstones are believed to remove negative energies and bring
            positivity. Each zodiac sign has a specific gemstone that may
            enhance luck, health, and success. They are said to transfer
            energies through body contact and improve overall well-being.
          </p>
        </div>
      </div>

      <!-- FAQ Item -->
      <div class="faq-item">
        <button class="faq-question">
          Why is buying gemstones online feasible?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer">
          <p>
            Buying gemstones online saves time and offers access to certified,
            high-quality stones from trusted sellers. Online platforms provide
            detailed information, customization options, and doorstep delivery,
            making gemstone shopping convenient and reliable.
          </p>
        </div>
      </div>

    </div>

  </div>
</section>

@endsection
