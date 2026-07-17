# Thiết kế giao diện Frontend – Điện Máy Trường An PQ

> Tài liệu này định nghĩa bộ quy tắc thiết kế visual, CSS system, và UX chuẩn.
> Đọc file này khi tạo hoặc chỉnh sửa giao diện để đảm bảo tính nhất quán Premium UI/UX.

---

## 1. CSS Variables System (`public/frontend/css/style.css`)

```css
:root {
    /* Màu chủ đạo */
    --primary-color: #c0392b;      /* Đỏ chủ đạo thương hiệu */
    --accent-color: #f39c12;       /* Vàng nhấn */
    --primary-rgb: 192, 57, 43;

    /* Nền & bề mặt */
    --bg-light: #f8f9fa;
    --bg-card: #ffffff;
    --border-light: #e9ecef;

    /* Chữ */
    --text-dark: #1a1a2e;
    --text-muted: #6c757d;

    /* Font */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Inter', sans-serif;

    /* Bo góc & bóng đổ */
    --border-radius: 8px;
    --border-radius-lg: 16px;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.12);

    /* Hiệu ứng */
    --transition: all 0.3s ease;
}
```

---

## 2. Grid System

```css
/* Desktop */
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }

/* Tablet (992px) */
@media (max-width: 992px) {
    .grid-4 { grid-template-columns: repeat(2, 1fr); }
    .grid-3 { grid-template-columns: repeat(2, 1fr); }
}

/* Mobile (768px) – 2 cột cho products và news */
@media (max-width: 768px) {
    .grid-4, .grid-3 { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .grid-2 { grid-template-columns: 1fr; }
}
```

---

## 3. Cấu trúc File Frontend

```
resources/views/frontend/
├── master.blade.php             # Layout chính: head + header/mobi + slider + content + footer + buttons + script
├── partials/
│   ├── head.blade.php           # <head>: meta SEO, favicon, CSS với cache busting (?v={{ filemtime() }})
│   ├── header.blade.php         # Topbar (phone/email/address) + Logo + Search + Cart + Nav categories + Menu
│   ├── header-mobi.blade.php    # Hamburger + Logo + Cart icon
│   ├── menumobi.blade.php       # Sidebar slide-in: logo + menu + categories + contact info
│   ├── footer.blade.php         # 4 cột: about/social | quick links | contact info+map | footer_pages
│   ├── slider.blade.php         # Hero slider: loop $sliders → <img> + pagination dots
│   ├── buttons.blade.php        # Floating bên PHẢI: hotline + zalo + back-to-top
│   └── script.blade.php         # JS: slider autoplay, AJAX cart/switch/stt, search suggestions, mobi menu
└── modules/
    ├── home/index.blade.php     # Features block + news + hot_products + category loop + brands
    ├── product/
    │   ├── category.blade.php   # Sidebar filter + grid sản phẩm + phân trang
    │   └── detail.blade.php     # Ảnh + thông tin + qty + add-to-cart + related products
    ├── news/
    │   ├── category.blade.php   # Grid bài viết + phân trang
    │   └── detail.blade.php     # Nội dung bài viết + related news
    ├── page/index.blade.php     # Hiển thị nội dung trang tĩnh
    ├── contact/index.blade.php  # Form liên hệ + map + thông tin $website
    └── search/index.blade.php   # Kết quả tìm kiếm sản phẩm
```

---

## 4. Component Patterns – Blade chuẩn

### Product Card
```blade
<div class="product-card">
    @if($prod->price_old && $prod->price_old > $prod->price)
        <div class="product-badge sale">SALE</div>
    @elseif($prod->hot)
        <div class="product-badge">HOT</div>
    @endif
    <a href="{{ route('web.resolve', ['slug' => $prod->slug]) }}" class="product-image-container">
        <img src="{{ $prod->image }}" alt="{{ $prod->name }}">
    </a>
    <div class="product-details">
        <div class="product-cat">{{ $prod->cate->name ?? '' }}</div>
        <a href="{{ route('web.resolve', ['slug' => $prod->slug]) }}" class="product-title">{{ $prod->name }}</a>
        <div class="product-price-box">
            @if($prod->price == 0)
                <span class="product-price">Giá: Liên hệ</span>
            @else
                <span class="product-price">{{ number_format($prod->price, 0, ',', '.') }}đ</span>
                @if($prod->price_old)
                    <span class="product-price-old">{{ number_format($prod->price_old, 0, ',', '.') }}đ</span>
                @endif
            @endif
        </div>
        <div class="product-actions">
            <a href="{{ route('web.resolve', ['slug' => $prod->slug]) }}" class="btn btn-outline">Chi tiết</a>
            <button class="btn btn-primary ajax-add-to-cart" data-uuid="{{ $prod->uuid }}">
                <i class="fa-solid fa-cart-plus"></i>
            </button>
        </div>
    </div>
</div>
```

### News Card
```blade
<div class="news-card">
    <a href="{{ route('web.resolve', ['slug' => $post->slug]) }}" class="news-card-img-link">
        <img src="{{ $post->image }}" alt="{{ $post->name }}" style="width:100%;height:100%;object-fit:cover;">
    </a>
    <div class="news-card-body">
        <span class="news-card-tag">{{ $post->cate->name ?? 'Tin tức' }}</span>
        <a href="{{ route('web.resolve', ['slug' => $post->slug]) }}" class="news-card-title">
            {{ $post->name }}
        </a>
        <p class="news-card-intro">{{ $post->intro_vn }}</p>
        <a href="{{ route('web.resolve', ['slug' => $post->slug]) }}" class="news-card-more">
            Đọc thêm <i class="fa-solid fa-angles-right"></i>
        </a>
    </div>
</div>
```

### Slider
```blade
@if(isset($sliders) && !$sliders->isEmpty())
<section class="hero-slider" id="hero-slider">
    @foreach($sliders as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ asset($slide->image) }}" alt="Slide {{ $index+1 }}" class="slide-img">
        </div>
    @endforeach
    <div class="slider-pagination">
        @foreach($sliders as $index => $slide)
            <span class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
        @endforeach
    </div>
</section>
@endif
```

---

## 5. Quy tắc UX & Thẩm mỹ

### Bắt buộc:
- **Hover effect**: Tất cả card có `transform: translateY(-4px)` + `box-shadow` khi hover
- **Button transition**: `transition: all 0.3s ease` cho mọi button và link
- **Mobile 2 col**: Products và news luôn hiển thị 2 cột trên mobile (không 1 cột)
- **Floating buttons**: Luôn đặt `position: fixed; right: 20px` — không được che nội dung
- **Font import**: Google Fonts import trong head.blade.php
- **Image aspect ratio**: Product images tỷ lệ 1:1, News images tỷ lệ 16:9

### CSS cho Card hover:
```css
.product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.news-card:hover { transform: translateY(-4px); }
.product-card, .news-card { transition: var(--transition); }
```

### Slider CSS chuẩn (không bị vỡ layout):
```css
.hero-slider { position: relative; overflow: hidden; }
.slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.8s ease; pointer-events: none; }
.slide.active { position: relative; opacity: 1; pointer-events: auto; }
.slide-img { width: 100%; height: auto; display: block; }
```

---

## 6. Responsive Breakpoints

| Breakpoint | Áp dụng |
|---|---|
| `max-width: 1200px` | Container thu nhỏ |
| `max-width: 992px` | Grid 4→2, ẩn desktop nav, hiện mobi header |
| `max-width: 768px` | Grid 3→2 cho news/products, font size nhỏ hơn |
| `max-width: 576px` | Padding giảm, font nhỏ nhất, stacking full |

---

## 7. Thẩm mỹ phù hợp theo chủ đề

| Chủ đề | Primary Color | Font Heading | Accent |
|---|---|---|---|
| Điện máy / Công nghiệp | `#c0392b` (đỏ) | Montserrat | `#f39c12` (vàng) |
| Bất động sản | `#d4af37` (vàng kim) | Playfair Display | `#1a1a2e` (navy) |
| Du lịch | `#0077b6` (xanh dương) | Outfit | `#48cae4` (xanh nhạt) |
| Thời trang | `#2d2d2d` (đen) | Cormorant Garamond | `#c9a96e` (vàng nhạt) |
| Nhà hàng / Ẩm thực | `#8b1a1a` (đỏ nâu) | Lora | `#d4a017` (vàng đất) |
