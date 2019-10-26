@extends('emails.layout')

@section('main')
    <h1>{{$title}}</h1>
    <p>
        Dear <b>{{ $firstname }}</b>,Your account has been created in 
        <a href="https://app.ticsol.com.au">app.ticsol.com.au</a>, please 
        login to your account with the following credentials:
        <br>
        <br>
        E-mail: {{ $email }}
        <br>
        Password: {{ $password }}

        <br>
        <b>For security reasons, please change your password when you logged in to your account.</b>
        
        <br>
        <br>
        
        Regards, 
        <br>
        TicSol support team
    </p>
@endsection