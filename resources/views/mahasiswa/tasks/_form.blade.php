@php
    $isEdit = isset($task);
    $deadlineValue = old('deadline', $isEdit ? $task->deadline->format('Y-m-d\TH:i') : '');
@endphp

<form action="{{ $isEdit ? route('mahasiswa.tasks.update', $task) : route('mahasiswa.tasks.store') }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label for="judul" class="form-label">Judul tugas <span class="text-danger">*</span></label>
            <input id="judul" type="text" name="judul" value="{{ old('judul', $task->judul ?? '') }}"
                   class="form-control @error('judul') is-invalid @enderror" maxlength="255" required>
            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror"
                      placeholder="Catatan atau detail tugas (opsional)">{{ old('deskripsi', $task->deskripsi ?? '') }}</textarea>
            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="course_id" class="form-label">Mata kuliah <span class="text-danger">*</span></label>
            <select id="course_id" name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                <option value="">Pilih mata kuliah</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected((string) old('course_id', $task->course_id ?? '') === (string) $course->id)>{{ $course->nama }}</option>
                @endforeach
            </select>
            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @if($courses->isEmpty())
                <div class="form-text text-danger">Belum ada mata kuliah. Hubungi admin untuk menambahkan data terlebih dahulu.</div>
            @endif
        </div>

        <div class="col-md-6">
            <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
            <input id="deadline" type="datetime-local" name="deadline" value="{{ $deadlineValue }}"
                   class="form-control @error('deadline') is-invalid @enderror" required>
            @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="belum" @selected(old('status', $task->status ?? 'belum') === 'belum')>Belum dikerjakan</option>
                <option value="proses" @selected(old('status', $task->status ?? 'belum') === 'proses')>Dikerjakan</option>
                <option value="selesai" @selected(old('status', $task->status ?? 'belum') === 'selesai')>Selesai</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-primary" @disabled($courses->isEmpty())>
            <i class="bi bi-save me-1"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Tugas' }}
        </button>
        <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
