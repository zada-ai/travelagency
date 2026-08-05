@extends('admin.layouts.app')

@section('title', 'Edit Hotel')
@section('page-heading', 'Edit Hotel')
@section('page-description', 'Update hotel details and operational settings.')

@section('content')
    @include('admin.hotels._wizard-form', [
        'formAction' => route('admin.hotels.update', $hotel),
        'submitLabel' => 'Update Hotel',
        'hotel' => $hotel,
        'isEdit' => true,
    ])
@endsection
