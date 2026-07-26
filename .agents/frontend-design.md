# Quy Chuẩn Thiết Kế Giao Diện Frontend (WordPress Style + Tailwind CSS)

> Tài liệu này quy định toàn bộ Design System cho Frontend, kết hợp sự thân thiện/đẹp mắt của WordPress và sự tối ưu của Tailwind CSS.

---

## 1. TYPOGRAPHY & FONTS (WORDPRESS STYLE)

Tất cả các file CSS/Tailwind phải cấu hình font family theo chuẩn:

```css
/* public/frontend/css/fonts.css */
@font-face {
  font-family: 'Noto Sans';
  src: url('/frontend/fonts/NotoSans-Regular.woff2') format('woff2');
  font-weight: 400;
  font-display: swap;
}
@font-face {
  font-family: 'Roboto';
  src: url('/frontend/fonts/Roboto-Bold.woff2') format('woff2');
  font-weight: 700;
  font-display: swap;
}

body {
  font-family: "Noto Sans", Roboto, sans-serif;
  color: #333333;
  line-height: 1.6;
  background-color: #f8fafc;
}

h1, h2, h3, h4, h5, h6 {
  font-family: "Roboto", "Noto Sans", sans-serif;
  font-weight: 700;
  color: #0f172a;
}
```

---

## 2. HIỆU ỨNG SCROLL (SCROLL ANIMATIONS)

Sử dụng thư viện Scroll Animation nhẹ lưu tại local (`public/frontend/js/scroll-animate.js`) hoặc **Intersection Observer API**:

### Quy chuẩn thêm class cho Blade Component khi cuộn:

```html
<!-- Khi cuộn tới, phần tử này sẽ trượt nhẹ từ dưới lên và mờ dần ra -->
<div class="scroll-anim fade-up delay-100">...</div>
<div class="scroll-anim fade-up delay-200">...</div>
<div class="scroll-anim zoom-in delay-100">...</div>
```

### CSS Hiệu ứng chuẩn (`public/frontend/css/animations.css`):

```css
.scroll-anim {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  will-change: opacity, transform;
}
.scroll-anim.is-visible {
  opacity: 1;
  transform: translateY(0);
}
.scroll-anim.zoom-in {
  transform: scale(0.9);
}
.scroll-anim.zoom-in.is-visible {
  transform: scale(1);
}
/* Stagger delays */
.delay-100 { transition-delay: 100ms; }
.delay-200 { transition-delay: 200ms; }
.delay-300 { transition-delay: 300ms; }
```

---

## 3. THIẾT KẾ COMPONENT CHUẨN WORDPRESS (TAILWIND CLASS)

### A. Product Card (Thẻ sản phẩm)

```html
<div class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 ease-in-out border border-slate-100 overflow-hidden scroll-anim fade-up">
  <div class="aspect-square w-full overflow-hidden bg-slate-100 relative">
    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
    @if($product->price_old > $product->price)
      <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">-{{ round((($product->price_old - $product->price)/$product->price_old)*100) }}%</span>
    @endif
  </div>
  <div class="p-4">
    <p class="text-xs text-slate-400 mb-1">{{ $product->cate->name }}</p>
    <h3 class="font-bold text-slate-800 text-sm md:text-base line-clamp-2 group-hover:text-primary transition-colors">
      <a href="{{ route('web.resolve', $product->slug) }}">{{ $product->name }}</a>
    </h3>
    <div class="mt-3 flex items-center justify-between">
      <div>
        <span class="text-base md:text-lg font-extrabold text-red-600">{{ number_format($product->price) }}đ</span>
        @if($product->price_old)
          <span class="text-xs text-slate-400 line-through ml-1">{{ number_format($product->price_old) }}đ</span>
        @endif
      </div>
      <button class="w-9 h-9 rounded-full bg-slate-100 group-hover:bg-primary group-hover:text-white flex items-center justify-center transition-colors">
        <i class="fas fa-shopping-bag text-sm"></i>
      </button>
    </div>
  </div>
</div>
```

### B. News Card (Thẻ tin tức)

```html
<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden group scroll-anim fade-up">
  <div class="aspect-video overflow-hidden">
    <img src="{{ $news->image }}" alt="{{ $news->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
  </div>
  <div class="p-4 md:p-5">
    <span class="text-xs font-semibold text-primary bg-primary/10 px-2.5 py-1 rounded-md">{{ $news->cate->name }}</span>
    <h3 class="font-bold text-slate-800 text-base mt-2 mb-2 line-clamp-2 group-hover:text-primary transition-colors">
      <a href="{{ route('web.resolve', $news->slug) }}">{{ $news->name }}</a>
    </h3>
    <p class="text-slate-500 text-xs md:text-sm line-clamp-2 mb-4">{{ $news->intro_vn }}</p>
    <div class="text-xs text-slate-400 flex items-center gap-2">
      <i class="far fa-calendar-alt"></i>
      <span>{{ $news->created_at->format('d/m/Y') }}</span>
    </div>
  </div>
</div>
```

---

## 4. QUY CHUẨN RESPONSIVE GRID (TAILWIND)

* **Container:** `container mx-auto px-4 max-w-7xl`
* **Sản phẩm (Products Grid):**
  * Mobile (`< 640px`): `grid grid-cols-2 gap-3` (2 cột theo đúng yêu cầu)
  * Tablet (`640px - 1024px`): `grid-cols-3 gap-4`
  * Desktop (`> 1024px`): `grid-cols-4 gap-6`

* **Tin tức (News Grid):**
  * Mobile (`< 640px`): `grid grid-cols-2 gap-3`
  * Desktop (`> 1024px`): `grid-cols-3 gap-6`
