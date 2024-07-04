@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 profile-header">
                <h1>{{ $registration->name }} Details</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img style="height: 400px; width: 350px;" src="{{ $registration->getMedia('images')[0]?->getUrl() ?? '' }}"
                    alt="{{ $registration->getMedia('images')[0]?->name ?? '' }}" class="profile-image img-fluid">
            </div>
            <div class="col-md-8 profile-details">
                <div class="row">
                    <div class="col-md-3"><strong>Name</strong></div>
                    <div class="col-md-3">{{ $registration->name }}</div>
                    <div class="col-md-3"><strong>Father Name</strong></div>
                    <div class="col-md-3">{{ $registration->father_name }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>CNIC</strong></div>
                    <div class="col-md-3">{{ $registration->cnic }}</div>
                    <div class="col-md-3"><strong>Address</strong></div>
                    <div class="col-md-3">{{ $registration->address }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>Registration Date</strong></div>
                    <div class="col-md-3">{{ $registration->registration_date }}</div>
                    <div class="col-md-3"><strong>Phone</strong></div>
                    <div class="col-md-3">{{ $registration->phone_no }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>WhatsApp</strong></div>
                    <div class="col-md-3">{{ $registration->whatsapp_no }}</div>
                    <div class="col-md-3"><strong>DOB</strong></div>
                    <div class="col-md-3">{{ $registration->dob }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>Email</strong></div>
                    <div class="col-md-3">{{ $registration->email }}</div>
                    <div class="col-md-3"><strong>Status</strong></div>
                    <div class="col-md-3">{{ $registration->status }}</div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>Floor No</strong></div>
                    <div class="col-md-3">{{ $registration->floor->floor_name }}</div>
                    <div class="col-md-3"><strong>Room No</strong></div>
                    <div class="col-md-3">{{ $registration->room->room_name }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
@endsection
