{{-- resources/views/admin/modules/menu/menu-item.blade.php --}}
@props(['item', 'level' => 1, 'pageContent' => null, 'cateNew' => null, 'cateProduct' => null])

@php
    $nestedClass = 'nested-' . $level;
    $belongName = getMenuBelongName($item, $pageContent, $cateNew, $cateProduct);
    $typeLabel = getMenuTypeLabel($item->type);
    $viewUrl = getUrlMenu($item);
@endphp

<div class="list-group-item {{ $nestedClass }}" style="padding-right: unset">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h6 class="{{ $level == 1 ? 'fs-15 mb-1' : 'mb-1' }}">{{ $item->name_vn }}</h6>
            <p class="mb-0 text-muted small">
                Thuộc: {{ $typeLabel }}
                @if ($belongName)
                    : {{ $belongName }}
                @endif
            </p>
        </div>
        <div class="ms-3">
            @if ($viewUrl && $viewUrl !== route('web.404') && $viewUrl !== '#')
                <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" 
                   class="btn btn-primary btn-sm me-1">Xem</a>
            @endif
            <button class="btn btn-secondary btn-sm waves-effect waves-light" 
                    data-bs-toggle="modal" 
                    data-bs-target="#showModal{{ $item->id_menu }}">Sửa</button>
            <a href="{{ route('admin.menu.destroy', ['uuid' => $item->uuid]) }}" 
               onclick="return confirm('Xác nhận xóa menu ?')" 
               class="btn btn-sm btn-danger remove-item-btn">Xóa</a>
        </div>
    </div>

    {{-- Render children recursively --}}
    @if ($item->children && $item->children->isNotEmpty())
        <div class="list-group nested-list nested-sortable">
            @foreach ($item->children as $child)
                @include('admin.modules.menu.menu-item', [
                    'item' => $child,
                    'level' => $level + 1,
                    'pageContent' => $pageContent,
                    'cateNew' => $cateNew,
                    'cateProduct' => $cateProduct
                ])
            @endforeach
        </div>
    @endif
</div>

{{-- Modal for this menu item --}}
<div class="modal fade" id="showModal{{ $item->id_menu }}" tabindex="-1" 
     aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <div>
                    <h5 class="modal-title" id="exampleModalLabel">Cập nhật menu</h5>
                    <p class="mb-0 text-muted small">
                        Thuộc: {{ $typeLabel }}
                        @if ($belongName)
                            : {{ $belongName }}
                        @endif
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                        aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" 
                  action="{{ route('admin.menu.update', ['uuid' => $item->uuid]) }}" 
                  method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customername-field" class="form-label">Tiêu đề VN</label>
                        <input type="text" name="name_vn" value="{{ $item->name_vn }}" 
                               class="form-control" placeholder="Enter Name" required="">
                    </div>
                    @if ($item->type == 'link')
                        <div class="mb-3">
                            <label for="customername-field" class="form-label">Đường dẫn</label>
                            <input type="text" name="link" value="{{ $item->link }}" 
                                   class="form-control" placeholder="Enter Link" required="">
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="email-field" class="form-label">STT</label>
                        <input type="number" value="{{ $item->stt }}" name="stt" 
                               class="form-control" placeholder="Enter STT" required="">
                    </div>
                    <div>
                        <label for="status-field" class="form-label">Tình trạng</label>
                        <select class="form-control" data-trigger="" name="status" 
                                id="status-field" required="">
                            <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Bật</option>
                            <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display: block;">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" 
                                data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" 
                                id="add-btn">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>