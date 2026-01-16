@extends('admin.layout')

@section('content')
    <h1 class="text-2xl font-bold">File Details</h1>
    <p>This page will show details for file: {{ $file->original_name }}</p>
@endsection