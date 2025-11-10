@php
    $fonts = \App\Models\Font::where('is_active', true)->get();
@endphp

@foreach($fonts as $font)
    @if($font->type === 'google' && $font->css_url)
        <link rel="stylesheet" href="{{ $font->css_url }}">
    @elseif($font->type === 'upload' && $font->file_path)
        <style>
            @font-face {
                font-family: '{{ $font->name }}';
                src: url('{{ asset($font->file_path) }}') format('woff2'),
                     url('{{ asset($font->file_path) }}') format('woff'),
                     url('{{ asset($font->file_path) }}') format('truetype');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }
        </style>
    @endif
@endforeach