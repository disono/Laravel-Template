@extends('emails.layout')

@section('content')
    {{-- Greeting --}}
    Hello {{$full_name}}!

    <p><strong>New's and blog subscription</strong></p>

    {{-- Pages --}}
    @foreach ($pages as $row)
        <h4>[<a href="{{url($row->slug)}}">{{$row->name}}</a>]</h4>
        <p style="{{ $style['paragraph'] }}">
            {!! $row->mini_content !!}
        </p>
    @endforeach
@endsection
