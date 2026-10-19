@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('mahasiswa.tasks.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Tugas Saya
        </a>
        <h2 class="fw-bold mt-3 mb-1">Edit Tugas</h2>
        <p class="text-muted mb-0">Perbarui informasi dan status tugasmu.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('mahasiswa.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        class="form-control @error('judul') is-invalid @enderror"
                        value="{{ old('judul', $task->judul) }}"
                        maxlength="255"
                        required
                    >
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="course_id" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                    <select
                        id="course_id"
                        name="course_id"
                        class="form-select @error('course_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach ($courses as $course)
                            <option
                                value="{{ $course->id }}"
                                @selected(old('course_id', $task->course_id) == $course->id)
                            >
                                {{ $course->kode }} — {{ $course->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                    >{{ old('deskripsi', $task->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deadline" class="form-label">Tenggat Waktu <span class="text-danger">*</span></label>
                    <input
                        type="datetime-local"
                        id="deadline"
                        name="deadline"
                        class="form-control @error('deadline') is-invalid @enderror"
                        value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}"
                        required
                    >
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status Tugas <span class="text-danger">*</span></label>
                    <select
                        id="status"
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >
                        <option value="belum" @selected(old('status', $task->status) === 'belum')>
                            Belum dikerjakan
                        </option>
                        <option value="proses" @selected(old('status', $task->status) === 'proses')>
                            Sedang dikerjakan
                        </option>
                        <option value="selesai" @selected(old('status', $task->status) === 'selesai')>
                            Selesai
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('mahasiswa.tasks.show', $task->id) }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
