@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Upload Absensi Excel</h1>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 p-3 mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 p-3 mb-4">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="file" name="file" class="border p-2 rounded w-full" required>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
</form>
@endsection
