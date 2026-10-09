@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-shell">
    @include('components.dashboard-header')

    <div class="dashboard-workspace">
        @include('components.dashboard-sidebar')

        <main class="dashboard-main" aria-label="Konten dashboard"></main>
    </div>
</div>
@endsection
