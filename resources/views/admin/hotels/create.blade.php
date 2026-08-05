@extends('admin.layouts.app')

@section('title', 'Create Hotel')
@section('page-heading', 'Create Hotel')
@section('page-description', 'Add a new hotel to the Umrah ERP hotel inventory.')

@section('content')
    @include('admin.hotels._wizard-form', [
        'formAction' => route('admin.hotels.store'),
        'submitLabel' => 'Create Hotel',
        'isEdit' => false,
    ])
@endsection
