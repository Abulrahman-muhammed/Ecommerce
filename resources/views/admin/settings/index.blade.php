@extends('admin.layouts.master')

@section('title', 'Settings')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold"><i class="fas fa-cog me-2 text-primary"></i>General Settings</h3>
            </div>

            <x-alert />

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section 1: Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-secondary">Basic Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Site Name</label>
                                <input type="text" name="site_name" class="form-control" value="{{ $settings->site_name }}" placeholder="Enter site name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="site_email" class="form-control" value="{{ $settings->site_email }}" placeholder="contact@example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="site_phone" class="form-control" value="{{ $settings->site_phone }}" placeholder="+123456789">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Physical Address</label>
                                <textarea name="site_address" class="form-control" rows="2" placeholder="Street, City, Country">{{ $settings->site_address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Branding (Logo) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-secondary">Branding</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                @if($settings->logo)
                                    <div class="mb-3 p-2 border rounded bg-light d-inline-block">
                                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" style="max-height: 100px; max-width: 100%;">
                                    </div>
                                @else
                                    <div class="mb-3 p-4 border rounded bg-light d-inline-block text-muted">
                                        <i class="fas fa-image fa-3x"></i>
                                        <p class="small mb-0 mt-2">No Logo Uploaded</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Update Logo</label>
                                <input type="file" name="logo" class="form-control">
                                <div class="form-text text-muted">Recommended size: 250x100px (PNG/SVG)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Social Media Links -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-secondary">Social Media</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white"><i class="fab fa-facebook text-primary"></i></span>
                            <input type="url" name="facebook_url" class="form-control" value="{{ $settings->facebook_url }}" placeholder="https://facebook.com/your-page">
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white"><i class="fab fa-twitter text-info"></i></span>
                            <input type="url" name="twitter_url" class="form-control" value="{{ $settings->twitter_url }}" placeholder="https://twitter.com/your-handle">
                        </div>

                        <div class="input-group mb-0">
                            <span class="input-group-text bg-white"><i class="fab fa-instagram text-danger"></i></span>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $settings->instagram_url }}" placeholder="https://instagram.com/your-profile">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                        <i class="fas fa-save me-2"></i> Save All Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 12px; }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
    .input-group-text { border-right: none; }
    .input-group .form-control { border-left: none; }
</style>
@endsection