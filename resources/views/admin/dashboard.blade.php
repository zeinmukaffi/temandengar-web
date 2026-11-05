@extends('layouts.auth')

@section('title', 'Dashboard Konselor')

@section('content')

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h3 class="fw-bold">Dashboard TemanDengar</h3>

                <p class="text-secondary">
                    Halo, {{ auth()->user()->name }}!
                </p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button class="btn btn-outline-danger">
                    Logout
                </button>
            </form>

        </div>

        <div class="alert alert-success">
            Authentication berhasil! Konselor sudah login.
        </div>

    </div>

@endsection
