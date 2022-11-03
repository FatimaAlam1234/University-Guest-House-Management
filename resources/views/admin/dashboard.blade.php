@extends('admin.layouts.app')

@push('styles')
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endpush

@section('content')
<router-view></router-view>
<vue-progress-bar></vue-progress-bar>
@endsection

@push('script')
    @if(session('status'))
        <script>
            const message = @json(session('status'));
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            Toast.fire({
                icon: 'success',
                title: message
            })
        </script>
    @endif
@endpush