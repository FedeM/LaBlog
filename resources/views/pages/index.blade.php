<?php
use function Laravel\Folio\name;
use function Livewire\Volt\{state, mount};
 
name('dashboard');

mount(function() {
    // Check if the user is authenticated
    if (!auth()->check()) {
        // Redirect to login page if not authenticated
        return redirect()->route('login');
    }
});

?>
    @extends('adminlte::page')

    @section('title', 'Dashboard')

    @section('content_header')
        <h1>Dashboard</h1>
    @stop

    @section('content')
    <div>
        @volt
            <p>Welcome to this beautiful admin panel.</p>
        @endvolt
    </div>
    @stop