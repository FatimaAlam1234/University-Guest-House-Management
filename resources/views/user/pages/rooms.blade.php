@extends('user.layouts.app')

@section('header')
<div class="header-section">
    <h2>Available Rooms</h2>
</div>
@endsection

@section('content')
<section class="section section--rooms">
    @foreach ($rooms as $key=>$room)
    <div class="card card--room" data-aos="zoom-in" data-aos-duration="1200" data-aos-offset="250" data-aos-delay="{{ $key * 50 }}" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/img/uploads/rooms/{{ $room->photo }}') center center/cover">
        <div class="card-header">
            <h3>{{ $room->name }}</h3>
        </div>
        <div class="card-body">
        </div>
        <div class="card-footer">
            <a href="/room/{{ $room->id }}/{{$check_in_date}}/{{$check_out_date}}" class="btn btn--link">Book Room</a>
        </div>
    </div>
    @endforeach
</section>

@endsection
