@extends('admin.layouts.app')

@section('title', 'Edit Package')
@section('page-heading', 'Edit Package')
@section('page-description', 'Update an existing dynamic Umrah package.')

@section('content')
<form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.packages._form', ['isEdit' => true, 'package' => $package])
</form>
@endsection
