<!-- Schema.org JSON-LD Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "@id": "{{ route('web.home') }}#organization",
    "name": "{{ $web->name_vn ?? 'Base' }}",
    "url": "{{ route('web.home') }}",
    "logo": "{{ $web->logo ?? '' }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "{{ $web->phone ?? '' }}",
        "contactType": "customer service",
        "email": "{{ $web->email ?? '' }}"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "@id": "{{ route('web.home') }}#website",
    "url": "{{ route('web.home') }}",
    "name": "{{ $web->name_vn ?? 'Base' }}",
    "publisher": {
        "@id": "{{ route('web.home') }}#organization"
    }
}
</script>

{{-- Schema cho chi tiết sản phẩm --}}
@if(isset($product_detail))
@php
    $prodName = lang($product_detail, 'name');
    $prodIntro = lang($product_detail, 'intro');
    $prodImage = $product_detail->image_vn ? asset($product_detail->image_vn) : '';
    $prodPrice = $product_detail->price ?? 0;
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $prodName }}",
    "image": "{{ $prodImage }}",
    "description": "{{ strip_tags($prodIntro) }}",
    "sku": "HT-{{ $product_detail->id_product }}",
    "offers": {
        "@type": "Offer",
        "url": "{{ request()->url() }}",
        "priceCurrency": "VND",
        "price": "{{ $prodPrice }}",
        "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}",
        "itemCondition": "https://schema.org/NewCondition",
        "availability": "https://schema.org/InStock",
        "seller": {
            "@id": "{{ route('web.home') }}#organization"
        }
    }
}
</script>
@endif

{{-- Schema cho chi tiết tin tức --}}
@if(isset($news_detail))
@php
    $newsName = lang($news_detail, 'name');
    $newsIntro = lang($news_detail, 'intro') ?? $newsName;
    $newsImage = $news_detail->image_vn ? asset($news_detail->image_vn) : '';
    $newsDatePublished = $news_detail->created_at ? $news_detail->created_at->toIso8601String() : now()->toIso8601String();
    $newsDateModified = $news_detail->updated_at ? $news_detail->updated_at->toIso8601String() : $newsDatePublished;
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $newsName }}",
    "image": [
        "{{ $newsImage }}"
    ],
    "datePublished": "{{ $newsDatePublished }}",
    "dateModified": "{{ $newsDateModified }}",
    "author": {
        "@type": "Organization",
        "name": "{{ $web->name_vn ?? 'Base' }}"
    },
    "publisher": {
        "@id": "{{ route('web.home') }}#organization"
    },
    "description": "{{ strip_tags($newsIntro) }}"
}
</script>
@endif

{{-- Schema cho Breadcrumb (đường dẫn chuyên mục / chi tiết) --}}
@if(isset($category_detail) || isset($product_detail) || isset($news_detail))
@php
    $items = [
        [
            'name' => 'Trang chủ',
            'url' => route('web.home')
        ]
    ];

    if (isset($category_detail)) {
        $items[] = [
            'name' => lang($category_detail, 'name'),
            'url' => request()->url()
        ];
    } elseif (isset($product_detail)) {
        if ($product_detail->cate) {
            $items[] = [
                'name' => lang($product_detail->cate, 'name'),
                'url' => route('web.resolve', ['slug' => lang($product_detail->cate, 'slug')])
            ];
        }
        $items[] = [
            'name' => lang($product_detail, 'name'),
            'url' => request()->url()
        ];
    } elseif (isset($news_detail)) {
        if ($news_detail->cate) {
            $items[] = [
                'name' => lang($news_detail->cate, 'name'),
                'url' => route('web.resolve', ['slug' => lang($news_detail->cate, 'slug')])
            ];
        }
        $items[] = [
            'name' => lang($news_detail, 'name'),
            'url' => request()->url()
        ];
    }
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($items as $index => $item)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $item['name'] }}",
            "item": "{{ $item['url'] }}"
        }{{ $index < count($items) - 1 ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif
