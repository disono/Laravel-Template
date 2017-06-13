@extends('emails.layout')

@section('content')
    <h2>This e-email is from Careers Page Form</h2>
    <p><i>Please do not reply.</i></p>

    <h4>From: {{$request->get('name')}}</h4>
    <p>Email: {{$request->get('email')}}</p>
    <p>Phone: {{$request->get('phone')}}</p>
    <p>Address: {{$request->get('address')}}</p>
    <p>Position Desired: {{$request->get('position')}}</p>
    <p>Availability: {{$request->get('availability')}}</p>
@endsection