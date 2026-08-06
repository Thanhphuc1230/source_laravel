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
                                                                        {!! renderMenuOptions($item) !!}
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
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($cate_product as $item)
                                                                    {!! renderCategoryCheckbox($item, 'id_cate_product') !!}
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_product">
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
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseTwo" aria-expanded="false"
                                                            aria-controls="genques-collapseTwo">
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
                                                                    {!! renderCategoryCheckbox($item, 'id_cate_new') !!}
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
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
                                                {{-- Brand --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingBrand">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseBrand" aria-expanded="false"
                                                            aria-controls="genques-collapseBrand">
                                                            Thương hiệu
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseBrand" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingBrand"
                                                        data-bs-parent="#genques-accordion">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @if(isset($brands) && count($brands) > 0)
                                                                    @foreach ($brands as $item)
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="object_ids[]" value="{{ $item->id_brand }}"
                                                                                id="formCheckBrand{{ $item->id_brand }}">
                                                                            <label class="form-check-label d-flex align-items-center gap-2"
                                                                                for="formCheckBrand{{ $item->id_brand }}">
                                                                                @if ($item->image)
                                                                                    <img src="{{ $item->image }}" alt="{{ $item->name_vn }}" style="height: 20px; width: auto; object-fit: contain;">
                                                                                @endif
                                                                                <span>{{ $item->name_vn }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">Chưa có thương hiệu nào.</p>
                                                                @endif
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="brand">
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
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
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
                                                                        {!! renderMenuOptions($item) !!}
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
                                                    @include('admin.modules.menu.menu-item', [
                                                        'item' => $item,
                                                        'level' => 1,
                                                        'pageContent' => $page_content,
                                                        'cateNew' => $cate_new,
                                                        'cateProduct' => $cate_product,
                                                        'brands' => $brands ?? null,
                                                    ])
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
