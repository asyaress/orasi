@extends('admin.layouts.app')

@section('title', 'Edit Orasi — Admin')
@section('page_title', 'Edit Orasi')
@section('page_subtitle', 'Perbarui data orasi dan file pendukung')

@section('content')
    <form method="post" action="{{ route('admin.orasi.update', $orasi) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.orasi._form', ['orasi' => $orasi])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.orasi.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-yellow" type="submit">Simpan Perubahan</button>
        </div>
    </form>
@endsection

