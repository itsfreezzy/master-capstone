@extends('layouts.websitelayout')

@section('title')
Amenities - UNILAB Bayanihan Center
@endsection

@section('content')
<div class="row">
    <h2 class="text-center">Amenities</h2>
    <ul>
        @foreach ($amenities as $amenity)
        @if ($amenity->description != NULL)
        <li>{{ $amenity->amenity }} - {{ $amenity->description }}</li>
        @else
        <li>{{ $amenity->amenity }}</li>
        @endif
        @endforeach
    </ul>
</div>
@endsection
