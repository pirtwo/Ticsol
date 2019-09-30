@extends('emails.layout')

@section('main')
    <h1>{{$title}}</h1>
    <p>
        Dear <b>{{ $firstname }}</b>, Your <b>{{ strtoupper($requestType) }}</b> request have been 
        <b>{{ strtoupper($requestStatus) }}</b>.
        <br>
        See more details <a href="https://server.dev/request/{{ $requestType }}/{{ $requestId }}/details">here</a>.
        
        <br>
        <br>
        
        Regards, 
        <br>
        TicSol support team
    </p>
@endsection