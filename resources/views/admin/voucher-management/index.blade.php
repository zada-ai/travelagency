@extends('admin.layouts.app')

@section('title', 'Voucher Management')
@section('page-heading', 'Voucher Management')
@section('page-description', 'Manage the visa provider details displayed on new Umrah vouchers.')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-1 fw-bold">
                    Voucher Provider
                </h5>

                <p class="text-muted mb-0 small">
                    Set the visa provider name and logo that will be used on new vouchers.
                </p>
            </div>

            <div class="card-body p-4">

                <form
                    action="{{ route('admin.voucher-management.logo') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    {{-- Company / Provider Name --}}
                    <div class="mb-4">
                        <label for="company_name" class="form-label fw-semibold">
                            Visa Provider / Company Name
                        </label>

                        <input
                            type="text"
                            name="company_name"
                            id="company_name"
                            class="form-control"
                            value="{{ old('company_name', $setting->company_name ?? '') }}"
                            placeholder="Enter visa provider or company name"
                            maxlength="255"
                            required
                        >

                        <div class="form-text">
                            This name will appear next to the provider logo on new vouchers.
                        </div>
                    </div>

                    {{-- Current Logo --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Current Provider Logo
                        </label>

                        <div class="border rounded-3 p-4 bg-light text-center">

                            @if($setting && $setting->logo)

                                <img
                                    src="{{ asset($setting->logo) }}"
                                    alt="Current Voucher Provider Logo"
                                    style="max-height:120px; max-width:260px; object-fit:contain;"
                                >

                            @else

                                <div class="text-muted py-4">
                                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                                    No provider logo uploaded yet.
                                </div>

                            @endif

                        </div>
                    </div>

                    {{-- Upload Logo --}}
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold">
                            Upload / Change Provider Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            id="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <div class="form-text">
                            JPG, JPEG, PNG or WEBP. Maximum size: 2MB.
                            Leave empty if you only want to change the provider name.
                        </div>
                    </div>

                    {{-- New Logo Preview --}}
                    <div
                        id="logoPreviewContainer"
                        class="border rounded-3 p-3 mb-4 text-center d-none"
                    >

                        <small class="text-muted d-block mb-2">
                            New Logo Preview
                        </small>

                        <img
                            id="logoPreview"
                            src=""
                            alt="New Logo Preview"
                            style="max-height:120px; max-width:260px; object-fit:contain;"
                        >

                    </div>

                    {{-- Save --}}
                    <button
                        type="submit"
                        class="btn btn-primary px-4 fw-semibold"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Save Voucher Settings
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('logo').addEventListener('change', function(event) {

    const file = event.target.files[0];

    const preview = document.getElementById('logoPreview');
    const container = document.getElementById('logoPreviewContainer');

    if (!file) {
        container.classList.add('d-none');
        preview.src = '';
        return;
    }

    preview.src = URL.createObjectURL(file);
    container.classList.remove('d-none');
});
</script>

@endsection