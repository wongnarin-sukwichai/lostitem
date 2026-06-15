<div class="p-3">
    @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label fw-bold">ชื่อทรัพย์สิน <span class="text-danger">*</span></label>
            <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                   value="{{ old('item_name', $lostItem->item_name ?? '') }}" required>
            @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">วันที่พบ <span class="text-danger">*</span></label>
            <input type="date" name="found_date" class="form-control @error('found_date') is-invalid @enderror"
                   value="{{ old('found_date', isset($lostItem) ? $lostItem->found_date?->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">ประเภท <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">-- เลือกประเภท --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->category_id }}" {{ old('category_id', $lostItem->category_id ?? '') == $cat->category_id ? 'selected' : '' }}>
                    {{ $cat->category_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">สถานที่ <span class="text-danger">*</span></label>
            <select name="location_id" class="form-select @error('location_id') is-invalid @enderror" required>
                <option value="">-- เลือกสถานที่ --</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->location_id }}" {{ old('location_id', $lostItem->location_id ?? '') == $loc->location_id ? 'selected' : '' }}>
                    {{ $loc->location_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold">รายละเอียด</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $lostItem->description ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">รูปภาพ</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @isset($lostItem)
            @if($lostItem->image)
            <div class="mt-2">
                <img src="{{ asset('uploads/'.$lostItem->image) }}" height="80" class="rounded border">
                <small class="text-muted ms-2">รูปปัจจุบัน</small>
            </div>
            @endif
            @endisset
        </div>
    </div>
</div>
