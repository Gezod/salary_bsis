@extends('layouts.app')

@section('title', 'Tambah Presensi')

@section('content')
  <h3>Tambah Data Presensi</h3>

  <form action="{{ route('presensi.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Tanggal Presensi</label>
      <input type="date" name="tanggal_presensi" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Jam Masuk</label>
      <input type="time" name="jam_in" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Jam Awal Istirahat</label>
      <input type="time" name="jam_awal_isti" class="form-control">
    </div>
    <div class="mb-3">
      <label>Jam Akhir Istirahat</label>
      <input type="time" name="jam_akhir_isti" class="form-control">
    </div>
    <div class="mb-3">
      <label>Jam Pulang</label>
      <input type="time" name="jam_pulang" class="form-control" required>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Kembali</a>
  </form>
@endsection
