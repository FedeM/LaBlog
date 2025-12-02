<?php

use App\Models\Tag;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use function Livewire\Volt\{state, mount};

// State properties
state([
    'tag' => null, // Will hold the fetched Tag model
    'name' => '',
]);

// Mount function
mount(function (int $id) { // Expecting the ID from the URL
    $this->tag = Tag::findOrFail($id); // Manually fetch the tag
    $this->name = $this->tag->name;
});

// Update action
$update = function () {
    $validated = $this->validate([
        'name' => [
            'required',
            'string',
            'min:3',
            Rule::unique('tags')->ignore($this->tag->id),
        ],
    ]);

    $this->tag->update([
        'name' => $this->name,
        'slug' => Str::slug($this->name),
    ]);

    session()->flash('success', 'Etiqueta actualizada con éxito.');

    return $this->redirect('/tags');
};

?>
@volt
@extends('adminlte::page')

@section('title', 'Editar Etiqueta')

@section('content_header')
    <h1>Editar Etiqueta: {{ $name }}</h1>
@stop

@section('content')
<form wire:submit="update">
    <div class="card">
        <div class="card-body">
            <x-adminlte-input name="name" label="Nombre" placeholder="Escribe el nombre de la etiqueta" wire:model="name" />
        </div>
        <div class="card-footer">
            <x-adminlte-button class="btn-flat" type="submit" label="Actualizar" theme="success" icon="fas fa-lg fa-save"/>
            <a href="{{ url('tags') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
@stop
@endvolt