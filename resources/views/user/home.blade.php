@extends('user.layouts.app')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush

@section('header')
<div class="header-div">
    <div class="header-text">
        <h1>MPUAT Guest House </h1>
        <p>Guest House of Maharana Pratap University of Agriculture and Technology.</p>
        {{-- <a href="#" class="btn btn-primary btn--header">Book Now</a> --}}
    </div>
    <div class="header-form">
        <form action="{{ route('user.checkRooms') }}" class="form form--header" method="POST">
            @csrf
                        @method('POST')
            <h2 class="form-title">Check Available Rooms</h2>
            <div class="form-group">
                <label for="check_in">Check In</label>
                <input type="date" name="check_in" id="check_in" min="<?= date('Y-m-d'); ?>" onchange="setCheckOut()" class="form-control" required>
            </div>
            @error('check_in')
                    <span style="color:red">{{$message}}</span>
                    @enderror
            <div class="form-group">
                <label for="check_out">Check Out</label>
                <input type="date" name="check_out" id="check_out" min="<?= date('Y-m-d'); ?>" onchange="setCheckIn()" class="form-control" required>
            </div>
            @error('check_out')
                    <span style="color:red">{{$message}}</span>
                    @enderror
            {{-- <div class="form-group">
                <label for="guests">Guests</label>
                <input type="number" name="guests" id="guests" class="form-control" placeholder="0">
            </div>
            <div class="form-group">
                <label for="rooms">Rooms</label>
                <input type="number" name="rooms" id="rooms" class="form-control" placeholder="0">
            </div> --}}
            <div class="form-group">
                <button type="submit" class="btn btn--form">Check Rooms</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="section section--about">
    <div class="title">
        <h2 data-aos="fade-right" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-out">MPUAt Guest House</h2>
        <p data-aos="fade-right" data-aos-delay="500" data-aos-duration="1000" data-aos-easing="ease-out">The University Guest house is located on the campus adjacent to the Vice Chancellors Bungalow. The Guest house is primarily meant for official guests of the University/participants of Seminars/ Workshops/ Conferences etc, organized by the University/Departments as well as those sponsored by the State Government and the UGC.</p>
        <p data-aos="fade-right" data-aos-delay="350" data-aos-duration="1000" data-aos-easing="ease-out">The Guest house provides beddings (bed sheet, pillows, blanket, mattress etc.). An inventory of articles for use in the rooms is available in each room. The guests are requested to check these items at the time of occupying the room. The Guests are responsible for proper use and upkeep of the materials/fixtures provided in the rooms and also in the guest house. They shall be liable to pay charges for any loss or damage caused during their stay.</p>
        {{-- <a href="#" class="btn btn--link" data-aos="fade-in" data-aos-delay="600" data-aos-duration="1000" data-aos-easing="ease-out">Read More</a> --}}
    </div>
    <div class="images">
        <div class="image" data-aos="fade-up" data-aos-offset="150" data-aos-delay="200" data-aos-duration="1000" data-aos-easing="ease-out">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzeGqzoKpXEgHIlwbyhABsbCEhcIbTfj3ETA&usqp=CAU">
        </div>
        <div class="image" data-aos="fade-up" data-aos-offset="150" data-aos-delay="300" data-aos-duration="1000" data-aos-easing="ease-out">
            <img src="{{'/img/guestHousempuat.jpeg'}}">
        </div>
    </div>
</div>

<div class="section section--rooms">
    @foreach ($rooms as $key=>$room)
    <div class="card card--room" data-aos="zoom-in" data-aos-duration="1200" data-aos-offset="250" data-aos-delay="{{ $key * 50 }}" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/img/uploads/rooms/{{ $room->photo }}') center center/cover;">
        <div class="card-header">
            <h3>{{ $room->name }}</h3>
        </div>
        <div class="card-body">
            <div class="price">
                <p>Price</p>
                <p><i class="fas fa-rupee-sign"></i>&nbsp;{{ $room->price }} <span>/pernight</span></p>
            </div>
            <div class="type">
                <p>Type</p>
                <p>{{ $room->type }}</p>
            </div>
            <div class="size">
                <p>Size</p>
                <p>{{ $room->size }} m<sup>2</sup></p>
            </div>
            <div class="capacity">
                <p>Capacity</p>
                <p>Maximum {{ $room->capacity }} person(s)</p>
            </div>
            <div class="bed-type">
                <p>Bed Type</p>
                <p>{{ $room->bed_type }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // AOS (for animation on scroll  https://michalsnik.github.io/aos/)
    AOS.init();
</script>
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
@endpush