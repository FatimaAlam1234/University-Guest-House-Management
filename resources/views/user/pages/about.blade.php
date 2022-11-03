@extends('user.layouts.app')

@section('header')
<div class="header-section">
    <h2>About Us</h2>
</div>
@endsection

@section('content')

@endsection

@push('scripts')
<script>
    // Active Links
    document.querySelector('[data-link="/about-us"]').classList.add('active')
</script>
@endpush