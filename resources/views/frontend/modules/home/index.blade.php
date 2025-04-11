@extends('frontend.master')

@section('title', 'Contemporary, Mid Century & Modern Furniture | Article')
@section('description', 'Browse Article\'s stylish catalog of contemporary, mid century & modern furniture from world renowned designers at accessible prices. Shop now!')

@section('content')
    <!-- Hero Section -->
    <section class="hero" style="background-image: url('https://ext.same-assets.com/4223622022/541219403.webp');">
      <div class="hero-content">
        <h1>Spring Clearance:</h1>
        <h2>Up to 50% off</h2>
        <p>Savings are in the air with tons of newly-added markdowns.</p>
        <div class="hero-cta">
          <a href="#" class="btn">Shop Now</a>
        </div>
      </div>
    </section>

    <!-- Blissed Out Section -->
    <section class="feature-section" style="background-image: url('https://ext.same-assets.com/4223622022/555687857.webp');">
      <div class="feature-content">
        <h2 class="feature-title">Blissed out.</h2>
        <p class="feature-text">Turn every space into a happy one with bright colors and mood-pleasing designs.</p>
        <a href="#" class="btn">Set the mood</a>
      </div>
    </section>

    <!-- Shop By Room Section -->
    <section class="container">
      <h2 class="section-title">Shop By Room</h2>
      <div class="room-grid">
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/2225945327.webp" alt="Living Room" />
            </div>
            <div class="room-name">Living Room</div>
          </a>
        </div>
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/3549127370.webp" alt="Bedroom" />
            </div>
            <div class="room-name">Bedroom</div>
          </a>
        </div>
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/3215431206.webp" alt="Dining Room & Kitchen" />
            </div>
            <div class="room-name">Dining Room & Kitchen</div>
          </a>
        </div>
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/2524012004.webp" alt="Home Office" />
            </div>
            <div class="room-name">Home Office</div>
          </a>
        </div>
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/1059135025.webp" alt="Outdoor" />
            </div>
            <div class="room-name">Outdoor</div>
          </a>
        </div>
        <div class="room-item">
          <a href="#">
            <div class="room-image">
              <img src="https://ext.same-assets.com/4223622022/2441814505.webp" alt="Entryway" />
            </div>
            <div class="room-name">Entryway</div>
          </a>
        </div>
      </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
      <div class="container">
        <div class="video-container">
          <video autoplay muted loop playsinline>
            <source src="https://ext.same-assets.com/1247681252/877011858.mp4" type="video/mp4">
            Your browser does not support the video tag.
          </video>
          <div class="video-overlay">
            <h2 class="video-title">Designed for living</h2>
            <p class="video-text">Furniture that fits the way you live</p>
            <a href="#" class="btn">Shop All</a>
          </div>
        </div>
      </div>
    </section>

    <!-- In Your Corner Section -->
    <section class="feature-section" style="background-image: url('https://ext.same-assets.com/4223622022/851861578.webp');">
      <div class="feature-content">
        <h2 class="feature-title">In your corner.</h2>
        <p class="feature-text">Loungers and ottomans that have your needs in mind.</p>
        <a href="#" class="btn">Lean back</a>
      </div>
    </section>

    <!-- Dual Features -->
    <section class="container">
      <div class="dual-features">
        <div class="feature-card" style="background-image: url('https://ext.same-assets.com/4223622022/2202460104.webp');">
          <div class="feature-card-content">
            <h3>Drinks on us.</h3>
            <p>The best seat in the house is at the bar.</p>
            <a href="#" class="btn">Shop Bar + Kitchen</a>
          </div>
        </div>
        <div class="feature-card" style="background-image: url('https://ext.same-assets.com/4223622022/3170680543.webp');">
          <div class="feature-card-content">
            <h3>Side (table) quest.</h3>
            <p>Our side tables are the main event.</p>
            <a href="#" class="btn">Shop Side Tables</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
      <div class="container">
        <h3 class="newsletter-title">Get new products and promotions in your inbox.</h3>
        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
          @csrf
          <input type="email" id="newsletter-email" name="email" placeholder="Your email address" required />
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </section>

    <!-- Shop Top Sellers -->
    <section class="container">
      <h2 class="section-title">Shop Top Sellers</h2>
      <div class="product-grid">
        @foreach($topSellingProducts as $product)
        <div class="product-card">
          <a href="{{ route('product.show', $product->slug) }}">
            <div class="product-image">
              <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="main-image" />
              <img src="{{ $product->hover_image }}" alt="{{ $product->name }}" class="hover-image" />
            </div>
            <h3 class="product-title">{{ $product->name }}</h3>
            <div class="product-price">${{ number_format($product->price, 0) }}</div>
          </a>
          <div class="color-options">
            @foreach($product->colors as $color)
            <div class="color-option" style="background-color: {{ $color->hex_code }};" data-color="{{ $color->name }}"></div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </section>

    <!-- Compartmentalize Section -->
    <section class="feature-section" style="background-image: url('https://ext.same-assets.com/4223622022/2424552677.webp');">
      <div class="feature-content">
        <h2 class="feature-title">Compartmentalize it.</h2>
        <p class="feature-text">Dressers to store your many, many things.</p>
        <a href="#" class="btn">Put it away</a>
      </div>
    </section>

    <!-- Style in the Wild -->
    <section class="container">
      <h2 class="section-title">Great style in the wild.</h2>
      <p class="text-center">Looking for inspo? Check out how our customers have styled their own Article furniture. <a href="#">See more</a></p>

      <div class="gallery-grid">
        <div class="gallery-item">
          <img src="https://ext.same-assets.com/84395286/2016099263.jpeg" alt="Customer's styled furniture" />
          <p>Photo by @jmlivingconcepts</p>
        </div>
        <div class="gallery-item">
          <img src="https://ext.same-assets.com/84395286/779274857.jpeg" alt="Customer's styled furniture" />
          <p>Photo by @emilyfaith.home</p>
        </div>
        <div class="gallery-item">
          <img src="https://ext.same-assets.com/84395286/2165857810.jpeg" alt="Customer's styled furniture" />
          <p>Photo by @_owaysis</p>
        </div>
      </div>
    </section>
@endsection

@push('scripts')
<script>
  // Any page-specific JavaScript can go here
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize any home page specific functionality
    console.log('Home page loaded');
  });
</script>
@endpush
