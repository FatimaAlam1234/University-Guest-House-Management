@extends('user.layouts.app')

@section('header')
<div class="header-section">
    <h2>Contact Us</h2>
</div>
@endsection

@section('content')

@endsection

@push('scripts')
<script>
    // Active Links
    document.querySelector('[data-link="/contact-us"]').classList.add('active')
</script>
@endpush