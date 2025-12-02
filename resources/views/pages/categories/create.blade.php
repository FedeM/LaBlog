<?php

use App\Models\Category;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, mount};

// State properties
state(['name' => '']);

// Save action
$save = function () {
    $validated = $this->validate([
        'name' => ['required', 'string', 'min:3', 'unique:categories,name'],
    ]);

    Category::create([
        'name' => $this->name,
        'slug' => Str::slug($this->name),
    ]);

    session()->flash('success', 'Categoría creada con éxito.');

    return $this->redirect('/categories');
};

?>

@volt

@extends('adminlte::page')

@section('title', 'Crear Nueva Categoría')

@section('content_header')
    <h1>Crear Nueva Categoría</h1>
@stop

@section('content')
<form wire:submit="save">
    <div class="card">
        <div class="card-body">
            <x-adminlte-input name="name" label="Nombre" placeholder="Escribe el nombre de la categoría" wire:model="name" />
        </div>
        <div class="card-footer">
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar Categoría" theme="success" icon="fas fa-lg fa-save"/>
            <a href="{{ url('categories') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
@stop
@endvolt