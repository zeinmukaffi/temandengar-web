@extends('layouts.auth')

@section('title', 'Beranda')

@section('content')

    <div class="container py-5 text-center">

        <h1 class="fw-bold text-primary">TemanDengar.</h1>

        <p class="text-secondary">
            Ruang aman untuk didengar, teman untuk bercerita.
        </p>

        <a href="{{ route('login') }}" class="btn btn-primary">
            Login Konselor
        </a>

    </div>

@endsection
