@extends('user.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
@endpush

@section('header')
<section class="room-section">
    <div class="container">
        <div class="row py-4 my-4">
            <div class="col-md-8">
                <img src="/img/uploads/rooms/{{ $room[0]->photo }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-4">
                <h1 class="h3 text-center mb-4 pb-4 border-bottom">{{ $room[0]->name }}</h1>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="float-left font-weight-bold">Price</span>
                        <span class="float-right"><i class="fas fa-rupee-sign"></i> {{ $room[0]->price }}/pernight</span>
                    </li>
                    <li class="list-group-item">
                        <span class="float-left font-weight-bold">Room Type</span>
                        <span class="float-right">{{ $room[0]->type }}</span>
                    </li>
                    <li class="list-group-item">
                        <span class="float-left font-weight-bold">Capacity</span>
                        <span class="float-right">Maximum of {{ $room[0]->capacity }} person(s)</span>
                    </li>
                    <li class="list-group-item">
                        <span class="float-left font-weight-bold">Room Size</span>
                        <span class="float-right">{{ $room[0]->size }}m<sup>2</sup></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="section-content border-top">
    <div class="container py-4 my-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-xl form-title">Please fill the form</h2>
            </div>
        </div>

        <form action="{{ url('/room/' . $room[0]->id) }}" method="POST">
            <div class="row">
                @csrf
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror">
                    </div>
                    @error('first_name')
                    <span class="text-danger text-sm">{{$message}}</span>
                    @enderror

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror">
                    </div>
                    @error('last_name')
                    <span class="text-danger text-sm">{{$message}}</span>
                    @enderror

                    <div class="form-group">
                        <label for="email_address">Email Address</label>
                        <input type="email" name="email_address" id="email_address" class="form-control @error('email_address') is-invalid @enderror">
                    </div>
                    @error('email_address')
                    <span class="text-danger text-sm">{{$message}}</span>
                    @enderror

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror"">
                    </div>
                    @error('phone_number')
                        <span class=" text-danger text-sm">{{$message}}</span>
                        @enderror

                        <div class="form-group">
                            <label for="street_address">Street Address</label>
                            <input type="text" name="street_address" id="street_address" class="form-control @error('street_address') is-invalid @enderror">
                        </div>
                        @error('street_address')
                        <span class="text-danger text-sm">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="check_in">Check In</label>
                            <input type="date" name="check_in" id="check_in" value={{$checkIn}} min={{$checkIn}} onchange="setCheckOut()" max={{$checkOut}} class="form-control @error('check_in') is-invalid @enderror">
                        </div>
                        @error('check_in')
                        <span class="text-danger text-sm">{{$message}}</span>
                        @enderror

                        <div class="form-group">
                            <label for="check_out">Check Out</label>
                            <input type="date" name="check_out" id="check_out" value={{$checkOut}} onchange="setCheckIn()" min={{$checkIn}} max={{$checkOut}}  class="form-control @error('check_out') is-invalid @enderror">
                        </div>
                        @error('check_out')
                        <span class="text-danger text-sm">{{$message}}</span>
                        @enderror

                        <div class="form-group">
                            <label for="guests">Number of Guests</label>
                            <input type="number" name="guests" id="guests" class="form-control @error('guests') is-invalid @enderror" placeholder="0">
                        </div>
                        @error('guests')
                        <span class="text-danger text-sm">{{$message}}</span>
                        @enderror

                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-block">Reserve Room</button>
                    </div>
                </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function setCheckOut() {
        const checkIn = document.getElementById('check_in').value;
        console.log(checkIn);
        document.getElementById('check_out').setAttribute("min", checkIn)
    }
    function setCheckIn() {
        const checkOut = document.getElementById('check_out').value;
        document.getElementById('check_in').setAttribute("max", checkOut)
    }
</script>
@if(session('success')){
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
    })

    Toast.fire({
        icon: 'success',
        title: @json(session('success'))
    })
</script>
}
@elseif(session('error')){
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 10000,
        })
    
        Toast.fire({
            icon: 'error',
            title: @json(session('error'))
        })
    </script>
    }
@endif
@endpush