@extends('emails.layout')

@section('content')
    <h2>This e-email is from Contact-Us Form</h2>
    <p><i>Please do not reply.</i></p>

    <h4>From: {{$request->get('name')}}</h4>
    <p>Email: {{$request->get('email')}}</p>
    <p>Phone: {{$request->get('phone')}}</p>

    <p>Message: {{$request->get('message')}}</p>
@endsection