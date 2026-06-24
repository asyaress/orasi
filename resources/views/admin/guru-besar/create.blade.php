@extends('admin.layouts.app')

@section('title', 'Tambah Guru Besar — Admin')
@section('page_title', 'Tambah Guru Besar')
@section('page_subtitle', 'Master data — dari kepegawaian atau input manual')

@section('content')
  @if (!empty($returnToOrasi) && $preselectOrasiId)
    <div class="alert alert-info py-2 small mb-3">
      Guru besar baru akan langsung ditugaskan ke orasi yang sedang Anda kelola.
    </div>
  @endif

    <form method="post" action="{{ route('admin.guru-besar.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.guru-besar._form', ['guruBesar' => null])

        <div class="admin-form-actions">
            @if (!empty($returnToOrasi) && $preselectOrasiId)
                <a href="{{ route('admin.orasi-ilmiah.show', $preselectOrasiId) }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            @else
                <a href="{{ route('admin.guru-besar.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            @endif
            <button class="btn btn-admin btn-admin-primary" type="submit"><i class="bi bi-check-lg"></i> Simpan</button>
        </div>
    </form>
@endsection
