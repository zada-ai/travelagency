@extends('admin.layouts.app')

@section('title', 'Build Package')
@section('page-heading', 'Build Package')
@section('page-description', 'Create a new dynamic Umrah package.')

@section('content')
<form action="{{ route('admin.packages.store') }}" method="POST">
    @csrf
    @include('admin.packages._form', ['isEdit' => false, 'package' => null])
</form>
@endsection
