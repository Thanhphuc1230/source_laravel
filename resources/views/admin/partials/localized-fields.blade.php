@props(['fields' => [], 'model' => $page ?? null])

@foreach ($fields as $field)
    @php
        $type = $field['type'] ?? 'text';
        $base = $field['base'];
        $label = $field['label'] ?? ucfirst($base);
        $rows = $field['rows'] ?? 6;
        $col = $field['col'] ?? 'col-12'; // Mặc định là full width
        $useEditor = $field['ckeditor'] ?? false;
        $languages = ['vn' => 'VN', 'en' => 'EN'];
    @endphp

    <div class="row">
        @foreach ($languages as $locale => $suffix)
            @php
                $name = $base.'_'.$locale;
                $id = ($useEditor) ? ($base.'-'.$locale) : null; 
                $value = old($name, data_get($model, $name, ''));
            @endphp
            
            <div class="{{ $col }} mb-3">
                <label for="{{ $id ?? $name }}" class="form-label">{{ $label }} {{ $suffix }}</label>
                
                @if ($type === 'textarea')
                    <textarea 
                        {{ $id ? 'id='.$id : '' }} 
                        class="form-control @error($name) is-invalid @enderror" 
                        name="{{ $name }}" 
                        rows="{{ $rows }}" 
                        placeholder="Enter {{ $label }}">{{ $value }}</textarea>
                @else
                    <input type="text" 
                        {{ $id ? 'id='.$id : '' }} 
                        class="form-control @error($name) is-invalid @enderror" 
                        name="{{ $name }}" 
                        value="{{ $value }}" 
                        placeholder="Enter {{ $label }}">
                @endif
                
                @error($name)<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        @endforeach
    </div>
@endforeach
