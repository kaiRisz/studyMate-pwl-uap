@php $isEdit = isset($course); @endphp

<form action="{{ $isEdit ? route('admin.courses.update', $course) : route('admin.courses.store') }}"
      method="POST" novalidate>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
            <input type="text" name="kode" value="{{ old('kode', $isEdit ? $course->kode : '') }}"
                   class="form-control @error('kode') is-invalid @enderror" placeholder="contoh: IF301" maxlength="20">
            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-8">
            <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $isEdit ? $course->nama : '') }}"
                   class="form-control @error('nama') is-invalid @enderror" placeholder="contoh: Pemrograman Web Lanjut">
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">SKS <span class="text-danger">*</span></label>
            <input type="number" name="sks" min="1" max="6" value="{{ old('sks', $isEdit ? $course->sks : '') }}"
                   class="form-control @error('sks') is-invalid @enderror" placeholder="1 - 6">
            @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-9">
            <label class="form-label">Dosen Pengampu <span class="text-danger">*</span></label>
            <input type="text" name="dosen" value="{{ old('dosen', $isEdit ? $course->dosen : '') }}"
                   class="form-control @error('dosen') is-invalid @enderror" placeholder="Nama dosen">
            @error('dosen') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Hari <span class="text-danger">*</span></label>
            <select name="hari" class="form-select @error('hari') is-invalid @enderror">
                <option value="">-- Pilih hari --</option>
                @foreach($hariList as $hari)
                    <option value="{{ $hari }}" @selected(old('hari', $isEdit ? $course->hari : '') === $hari)>{{ $hari }}</option>
                @endforeach
            </select>
            @error('hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
            <input type="time" name="jam_mulai"
                   value="{{ old('jam_mulai', $isEdit ? substr($course->jam_mulai, 0, 5) : '') }}"
                   class="form-control @error('jam_mulai') is-invalid @enderror">
            @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
            <input type="time" name="jam_selesai"
                   value="{{ old('jam_selesai', $isEdit ? substr($course->jam_selesai, 0, 5) : '') }}"
                   class="form-control @error('jam_selesai') is-invalid @enderror">
            @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Ruangan <span class="text-danger">*</span></label>
            <input type="text" name="ruangan" value="{{ old('ruangan', $isEdit ? $course->ruangan : '') }}"
                   class="form-control @error('ruangan') is-invalid @enderror" placeholder="contoh: Lab 1">
            @error('ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
