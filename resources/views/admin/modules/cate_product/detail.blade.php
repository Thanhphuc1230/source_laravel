@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Add')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form
                                    action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($action == 'edit')
                                        <input type="hidden" name="currentPage" value="{{ $currentPage }}">
                                    @endif
                                    <div class="row">
                                        @include('admin.partials.localized-fields', [
                                            'fields' => [
                                                ['base' => 'name', 'label' => 'Tiêu đề', 'col' => 'col-md-6'],
                                                ['base' => 'slug', 'label' => 'Slug', 'col' => 'col-md-6', 'type' => 'text'],
                                            ],
                                            'model' => $page ?? null,
                                        ])

                                        <div class="col-md-12 mb-3">
                                            <label for="parent_id" class="form-label">Danh mục cha</label>
                                            <select id="parent_id" class="form-select mb-3" aria-label="Default select example"
                                                name="parent_id">
                                                <option value="0"
                                                    {{ (old('parent_id') ?: $page->parent_id ?? '') == 0 ? 'selected' : '' }} style="font-weight: bold;">
                                                    Trang chính</option>
                                                @php
                                                    renderCategoryOptions(
                                                        $category,
                                                        0,
                                                        old('parent_id') ?: $page->parent_id ?? null,
                                                        'id_cate_product',
                                                    );
                                                @endphp
                                            </select>
                                        </div>

                                        @include('admin.partials.localized-fields', [
                                            'fields' => [
                                                ['base' => 'keyword', 'label' => 'Từ khóa', 'col' => 'col-md-6', 'rows' => 3, 'type' => 'textarea'],
                                                ['base' => 'description', 'label' => 'Mô tả ngắn', 'col' => 'col-md-6', 'rows' => 3, 'type' => 'textarea'],
                                            ],
                                            'model' => $page ?? null,
                                        ])
                                        
                                        @include('admin.partials.preview-link', ['model' => $page ?? null])
                                        @include('admin.partials.publishing-fields', ['model' => $page ?? null])

                                        @foreach ($adminLanguages as $locale)
                                            @include('admin.partials.image-upload', [
                                                'locale' => $locale,
                                                'imageFolder' => $imageFolder,
                                                'model' => $page ?? null,
                                            ])
                                        @endforeach

                                        @if ($action == 'create')
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_back" class="btn btn-primary"
                                                        value="Lưu và tạo mới">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Lưu và về danh sách">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Cập nhật">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#parent_id').select2();
    });
</script>
@endpush