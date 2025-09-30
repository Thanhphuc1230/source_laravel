@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Quản lý')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger mb-xl-0" role="alert" style="list-style-type: none">
                    @foreach ($errors->all() as $error)
                        <li>
                            <h4>{{ $error }}</h4>
                        </li>
                    @endforeach
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger mb-xl-0" role="alert" style="list-style-type: none">
                    <li>
                        <h4>{!! Session::get('error') !!}</h4>
                    </li>
                </div>
            @endif

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Quản lý trang menu</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row justify-content-evenly mb-4">
                                    <div class="col-lg-4">
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="flex-shrink-0 me-1">
                                                    <i class="ri-pencil-fill fs-24 align-middle text-success me-1"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-16 mb-0 fw-semibold">Chọn lựa</h5>
                                                </div>
                                            </div>

                                            <div class="accordion accordion-border-box" id="genques-accordion">
                                                {{-- category new --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#genques-collapseOne"
                                                            aria-expanded="false" aria-controls="genques-collapseOne">
                                                            Trang nội dung
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseOne" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingOne"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($page_content as $item)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="object_ids[]" value="{{ $item->id_page }}"
                                                                            id="formCheck{{ $item->id_page }}">
                                                                        <label class="form-check-label"
                                                                            for="formCheck{{ $item->id_page }}">
                                                                            {{ $item->name_vn }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        <option value="{{ $item->id_menu }}">
                                                                            {{ $item->name_vn }}</option>
                                                                        @if ($item->children)
                                                                            @foreach ($item->children as $child)
                                                                                <option value="{{ $child->id_page }}">
                                                                                    |---{{ $child->name_vn }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="page">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- category product --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingThree">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseThree" aria-expanded="false"
                                                            aria-controls="genques-collapseThree">
                                                            Danh mục sản phẩm
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseThree" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingThree"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                @foreach ($cate_product as $item)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="object_ids[]"
                                                                            value="{{ $item->id_cate_product }}"
                                                                            id="formCheck{{ $item->uuid }}">
                                                                        <label class="form-check-label"
                                                                            for="formCheck{{ $item->uuid }}">
                                                                            {{ $item->name_vn }}
                                                                        </label>
                                                                    </div>
                                                                    @if ($item->children)
                                                                        @foreach ($item->children as $child)
                                                                            <div class="form-check mb-2">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="object_ids[]"
                                                                                    value="{{ $child->id_cate_product }}"
                                                                                    id="formCheck{{ $child->uuid }}">
                                                                                <label class="form-check-label"
                                                                                    for="formCheck{{ $child->uuid }}">
                                                                                    |--{{ $child->name_vn }}
                                                                                </label>
                                                                            </div>
                                                                            @if ($child->children)
                                                                                @foreach ($child->children as $subChild)
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input"
                                                                                            type="checkbox"
                                                                                            name="object_ids[]"
                                                                                            value="{{ $subChild->id_cate_product }}"
                                                                                            id="formCheck{{ $child->uuid }}">
                                                                                        <label class="form-check-label"
                                                                                            for="formCheck{{ $subChild->uuid }}">
                                                                                            |--|--{{ $subChild->name_vn }}
                                                                                        </label>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        <option value="{{ $item->id_menu }}">
                                                                            {{ $item->name_vn }}</option>
                                                                        @if ($item->children)
                                                                            @foreach ($item->children as $child)
                                                                                <option value="{{ $child->id_menu }}">
                                                                                    |---{{ $child->name_vn }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type"
                                                                    value="cate_product">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingTwo">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo"
                                                            aria-expanded="false" aria-controls="genques-collapseTwo">
                                                            Danh mục tin tức
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseTwo" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingTwo"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($cate_new as $item)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="object_ids[]"
                                                                            value="{{ $item->id_cate_new }}"
                                                                            id="formCheck{{ $item->uuid }}">
                                                                        <label class="form-check-label"
                                                                            for="formCheck{{ $item->uuid }}">
                                                                            {{ $item->name_vn }}
                                                                        </label>
                                                                    </div>
                                                                    @if ($item->children)
                                                                        @foreach ($item->children as $child)
                                                                            <div class="form-check mb-2">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="object_ids[]"
                                                                                    value="{{ $child->id_cate_new }}"
                                                                                    id="formCheck{{ $child->uuid }}">
                                                                                <label class="form-check-label"
                                                                                    for="formCheck{{ $child->uuid }}">
                                                                                    |--{{ $child->name_vn }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        <option value="{{ $item->id_menu }}">
                                                                            {{ $item->name_vn }}</option>
                                                                        @if ($item->children)
                                                                            @foreach ($item->children as $child)
                                                                                <option value="{{ $child->id_page }}">
                                                                                    |---{{ $child->name_vn }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_new">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingFour">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseFour" aria-expanded="false"
                                                            aria-controls="genques-collapseFour">
                                                            Thêm liên kết
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseFour" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingFour"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="product-title-input">Tiêu đề
                                                                    </label>
                                                                    <input type="text" class="form-control"
                                                                        name="name_vn" value="{{ old('name_vn') }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="product-title-input">Đường dẫn</label>
                                                                    <input type="text" class="form-control"
                                                                        name="link" value="{{ old('link') }}">
                                                                </div>
                                                                <input type="hidden" name="type" value="link">
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        <option value="{{ $item->id_menu }}">
                                                                            {{ $item->name_vn }}</option>
                                                                        @if ($item->children)
                                                                            @foreach ($item->children as $child)
                                                                                <option value="{{ $child->id_page }}">
                                                                                    |---{{ $child->name_vn }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="link">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end accordion-->
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-3">
                                            @php
                                                // Helper to search nested category trees for a given id
                                                $searchInTree = function ($collection, $id, $idField = 'id_cate_new') {
                                                    foreach ($collection as $c) {
                                                        if (isset($c->{$idField}) && $c->{$idField} == $id) return $c;
                                                        if (!empty($c->children)) {
                                                            foreach ($c->children as $ch) {
                                                                if (isset($ch->{$idField}) && $ch->{$idField} == $id) return $ch;
                                                                if (!empty($ch->children)) {
                                                                    foreach ($ch->children as $sc) {
                                                                        if (isset($sc->{$idField}) && $sc->{$idField} == $id) return $sc;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    return null;
                                                };

                                                $typeLabels = [
                                                    'page' => 'Trang nội dung',
                                                    'cate_new' => 'Danh mục tin tức',
                                                    'cate_product' => 'Danh mục sản phẩm',
                                                    'link' => 'Liên kết',
                                                ];
                                            @endphp
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="flex-shrink-0 me-1">
                                                    <i class="ri-file-list-line fs-24 align-middle text-success me-1"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-16 mb-0 fw-semibold">Menu chính</h5>
                                                </div>
                                            </div>
                                            <div class="list-group col nested-list nested-sortable">
                                                @foreach ($menus as $item)
                                                    <div class="list-group-item nested-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="fs-15 mb-1">{{ $item->name_vn }}</h6>
                                                                <p class="mb-0 text-muted small">Thuộc: {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}@php $belongName=null; @endphp
                                                                    @if ($item->type == 'page' && isset($page_content))
                                                                        @php $b = $searchInTree($page_content, $item->object_id, 'id_page'); $belongName = $b->name_vn ?? null; @endphp
                                                                    @elseif ($item->type == 'cate_new' && isset($cate_new))
                                                                        @php $b = $searchInTree($cate_new, $item->object_id, 'id_cate_new'); $belongName = $b->name_vn ?? null; @endphp
                                                                    @elseif ($item->type == 'cate_product' && isset($cate_product))
                                                                        @php $b = $searchInTree($cate_product, $item->object_id, 'id_cate_product'); $belongName = $b->name_vn ?? null; @endphp
                                                                    @elseif ($item->type == 'link')
                                                                        @php $belongName = $item->link ?? null; @endphp
                                                                    @endif
                                                                    @if ($belongName) : {{ $belongName }} @endif
                                                                </p>
                                                            </div>
                                                            <div class="ms-3">
                                                                @php $viewUrl = getUrlMenu($item); @endphp
                                                                @if ($viewUrl && $viewUrl !== route('web.404') && $viewUrl !== '#')
                                                                    <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm me-1">Xem</a>
                                                                @endif
                                                                <button
                                                                    class="btn btn-secondary btn-sm waves-effect waves-light"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#showModal{{ $item->id_menu }}">Sửa</button>
                                                                <a href="{{ route('admin.menu.destroy', ['uuid' => $item->uuid]) }}"
                                                                    onclick="return confirm('Xác nhận xóa menu ?')"
                                                                    class="btn btn-sm btn-danger remove-item-btn">Xóa</a>
                                                            </div>
                                                        </div>

                                                        <div class="list-group nested-list nested-sortable">
                                                            @if ($item->children)
                                                                @foreach ($item->children as $child)
                                                                    <div class="list-group-item nested-2" style="padding-right: unset">
                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                            <div>
                                                                                <h6 class="mb-1">{{ $child->name_vn }}</h6>
                                                                                <p class="mb-0 text-muted small">Thuộc: {{ $typeLabels[$child->type] ?? ucfirst($child->type) }}@php $belongName=null; @endphp
                                                                                    @if ($child->type == 'page' && isset($page_content))
                                                                                        @php $b = $searchInTree($page_content, $child->object_id, 'id_page'); $belongName = $b->name_vn ?? null; @endphp
                                                                                    @elseif ($child->type == 'cate_new' && isset($cate_new))
                                                                                        @php $b = $searchInTree($cate_new, $child->object_id, 'id_cate_new'); $belongName = $b->name_vn ?? null; @endphp
                                                                                    @elseif ($child->type == 'cate_product' && isset($cate_product))
                                                                                        @php $b = $searchInTree($cate_product, $child->object_id, 'id_cate_product'); $belongName = $b->name_vn ?? null; @endphp
                                                                                    @elseif ($child->type == 'link')
                                                                                        @php $belongName = $child->link ?? null; @endphp
                                                                                    @endif
                                                                                    @if ($belongName) : {{ $belongName }} @endif
                                                                                </p>
                                                                            </div>
                                                                            <div class="ms-3">
                                                                                @php $viewUrl = getUrlMenu($child); @endphp
                                                                                @if ($viewUrl && $viewUrl !== route('web.404') && $viewUrl !== '#')
                                                                                    <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm me-1">Xem</a>
                                                                                @endif
                                                                                <button class="btn btn-secondary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#showModal{{ $child->id_menu }}">Sửa</button>
                                                                                <a href="{{ route('admin.menu.destroy', ['uuid' => $child->uuid]) }}" onclick="return confirm('Xác nhận xóa menu ?')" class="btn btn-sm btn-danger remove-item-btn">Xóa</a>
                                                                            </div>
                                                                        </div>
                                                                        {{-- subchild --}}
                                                                        @if ($child->children->isNotEmpty())
                                                                            <div
                                                                                class="list-group nested-list nested-sortable">
                                                                                @foreach ($child->children as $subchild)
                                                                                    <div class="list-group-item nested-3" style="padding-right: unset">
                                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                                            <div>
                                                                                                <h6 class="mb-1">{{ $subchild->name_vn }}</h6>
                                                                                                <p class="mb-0 text-muted small">Thuộc: {{ $typeLabels[$subchild->type] ?? ucfirst($subchild->type) }}@php $belongName=null; @endphp
                                                                                                    @if ($subchild->type == 'page' && isset($page_content))
                                                                                                        @php $b = $searchInTree($page_content, $subchild->object_id, 'id_page'); $belongName = $b->name_vn ?? null; @endphp
                                                                                                    @endif
                                                                                                    @if ($belongName) : {{ $belongName }} @endif
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="ms-3">
                                                                                                @php $viewUrl = getUrlMenu($subchild); @endphp
                                                                                                @if ($viewUrl && $viewUrl !== route('web.404') && $viewUrl !== '#')
                                                                                                    <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm me-1">Xem</a>
                                                                                                @endif
                                                                                                <button class="btn btn-secondary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#showModal{{ $subchild->id_menu }}">Sửa</button>
                                                                                                <a href="{{ route('admin.menu.destroy', ['uuid' => $subchild->uuid]) }}" onclick="return confirm('Xác nhận xóa menu ?')" class="btn btn-sm btn-danger remove-item-btn">Xóa</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Modal for parent item -->
                                                    <div class="modal fade" id="showModal{{ $item->id_menu }}"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel"
                                                        style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light p-3">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Cập
                                                                        nhật menu</h5>
                                                                    <p class="mb-0 text-muted small">Thuộc: {{ $typeLabels[$item->type] ?? ucfirst($item->type) }}@php $belongName=null; @endphp
                                                                        @if ($item->type == 'page' && isset($page_content))
                                                                            @php $b = $searchInTree($page_content, $item->object_id, 'id_page'); $belongName = $b->name_vn ?? null; @endphp
                                                                        @endif
                                                                        @if ($belongName) : {{ $belongName }} @endif
                                                                    </p>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"
                                                                        id="close-modal"></button>
                                                                </div>
                                                                <form class="tablelist-form"
                                                                    action="{{ route('admin.menu.update', ['uuid' => $item->uuid]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="customername-field"
                                                                                class="form-label">Tiêu đề VN</label>
                                                                            <input type="text" name="name_vn"
                                                                                value="{{ $item->name_vn }}"
                                                                                class="form-control"
                                                                                placeholder="Enter Name" required="">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="customername-field"
                                                                                class="form-label">Tiêu đề EN</label>
                                                                            <input type="text" name="name_en"
                                                                                value="{{ $item->name_en }}"
                                                                                class="form-control"
                                                                                placeholder="Enter Name" required="">
                                                                        </div>
                                                                        @if ($item->type == 'link')
                                                                            <div class="mb-3">
                                                                                <label for="customername-field"
                                                                                    class="form-label">Đường dẫn</label>
                                                                                <input type="text" name="link"
                                                                                    value="{{ $item->link }}"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Link" required="">
                                                                            </div>
                                                                        @endif
                                                                        <div class="mb-3">
                                                                            <label for="email-field"
                                                                                class="form-label">STT</label>
                                                                            <input type="number"
                                                                                value="{{ $item->stt }}"
                                                                                name="stt" class="form-control"
                                                                                placeholder="Enter STT" required="">
                                                                        </div>

                                                                        <div>
                                                                            <label for="status-field"
                                                                                class="form-label">Tình trạng</label>
                                                                            <select class="form-control" data-trigger=""
                                                                                name="status" id="status-field"
                                                                                required="">
                                                                                <option value="1"
                                                                                    {{ $item->status == 1 ? 'selected' : '' }}>
                                                                                    Bật</option>
                                                                                <option value="0"
                                                                                    {{ $item->status == 0 ? 'selected' : '' }}>
                                                                                    Tắt</option>
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

                                                    <!-- Modals for child items -->
                                                    @foreach ($item->children as $child)
                                                        <div class="modal fade" id="showModal{{ $child->id_menu }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            style="display: none;" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-light p-3">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Cập
                                                                            nhật menu con</h5>
                                                                        <p class="mb-0 text-muted small">Thuộc: {{ $typeLabels[$child->type] ?? ucfirst($child->type) }}@php $belongName=null; @endphp
                                                                            @if ($child->type == 'page' && isset($page_content))
                                                                                @php $b = $searchInTree($page_content, $child->object_id, 'id_page'); $belongName = $b->name_vn ?? null; @endphp
                                                                            @endif
                                                                            @if ($belongName) : {{ $belongName }} @endif
                                                                        </p>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal" aria-label="Close"
                                                                            id="close-modal"></button>
                                                                    </div>
                                                                    <form class="tablelist-form"
                                                                        action="{{ route('admin.menu.update', ['uuid' => $child->uuid]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label for="customername-field"
                                                                                    class="form-label">Tiêu đề VN</label>
                                                                                <input type="text" name="name_vn"
                                                                                    value="{{ $child->name_vn }}"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Name"
                                                                                    required="">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="customername-field"
                                                                                    class="form-label">Tiêu đề EN</label>
                                                                                <input type="text" name="name_en"
                                                                                    value="{{ $child->name_en }}"
                                                                                    class="form-control"
                                                                                    placeholder="Enter Name"
                                                                                    required="">
                                                                            </div>
                                                                            @if ($child->type == 'link')
                                                                                <div class="mb-3">
                                                                                    <label for="customername-field"
                                                                                        class="form-label">Đường dẫn</label>
                                                                                    <input type="text" name="link"
                                                                                        value="{{ $child->link }}"
                                                                                        class="form-control"
                                                                                        placeholder="Enter Link" required="">
                                                                                </div>
                                                                            @endif
                                                                            <div class="mb-3">
                                                                                <label for="email-field"
                                                                                    class="form-label">STT</label>
                                                                                <input type="number"
                                                                                    value="{{ $child->stt }}"
                                                                                    name="stt" class="form-control"
                                                                                    placeholder="Enter STT"
                                                                                    required="">
                                                                            </div>

                                                                            <div>
                                                                                <label for="status-field"
                                                                                    class="form-label">Tình trạng</label>
                                                                                <select class="form-control"
                                                                                    data-trigger="" name="status"
                                                                                    id="status-field" required="">
                                                                                    <option value="1"
                                                                                        {{ $child->status == 1 ? 'selected' : '' }}>
                                                                                        Bật</option>
                                                                                    <option value="0"
                                                                                        {{ $child->status == 0 ? 'selected' : '' }}>
                                                                                        Tắt</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer" style="display: block;">
                                                                            <div class="hstack gap-2 justify-content-end">
                                                                                <button type="button"
                                                                                    class="btn btn-light"
                                                                                    data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-success"
                                                                                    id="add-btn">Update</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                            <!--end accordion-->
                                        </div>
                                    </div>

                                    <div class="col-lg-2">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>

@endsection
