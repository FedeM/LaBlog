<?php

use App\Models\Tag;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, mount};

// State properties
state(['name' => '']);

// Save action
$save = function () {
    $validated = $this->validate([
        'name' => ['required', 'string', 'min:3', 'unique:tags,name'],
    ]);

    Tag::create([
        'name' => $this->name,
        'slug' => Str::slug($this->name),
    ]);

    session()->flash('success', 'Etiqueta creada con éxito.');

    return $this->redirect('/tags');
};

?>
@volt
@extends('adminlte::page')

@section('title', 'Crear Nueva Etiqueta')

@section('content_header')
    <h1>Crear Nueva Etiqueta</h1>
@stop

@section('content')
<form wire:submit="save">
    <div class="card">
        <div class="card-body">
            <x-adminlte-input name="name" label="Nombre" placeholder="Escribe el nombre de la etiqueta" wire:model="name" />
        </div>
        <div class="card-footer">
            <x-adminlte-button class="btn-flat" type="submit" label="Guardar Etiqueta" theme="success" icon="fas fa-lg fa-save"/>
            <a href="{{ url('tags') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
@stop
@endvolt