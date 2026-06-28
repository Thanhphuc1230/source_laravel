@props([
    'model' => $page ?? null,
    'col' => 'col-md-6'
])

<div class="{{ $col }} mb-3">
    <label for="stt" class="form-label">Số thứ tự</label>
    <input type="number" name="stt" class="form-control @error('stt') is-invalid @enderror"
        placeholder="Nhập số thứ tự" value="{{ old('stt', $model->stt ?? '') }}">
    @error('stt')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="{{ $col }} mb-3">
    <label for="created_at" class="form-label">Ngày đăng</label>
    <input type="datetime-local" id="created_at" name="created_at"
        class="form-control @error('created_at') is-invalid @enderror"
        value="{{ old('created_at', isset($model->created_at) ? \Carbon\Carbon::parse($model->created_at)->format('Y-m-d\TH:i') : '') }}">
    @error('created_at')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
