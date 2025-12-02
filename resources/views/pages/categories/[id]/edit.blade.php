<?php

use App\Models\Category;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use function Livewire\Volt\{state, mount};
// State properties
state([
    'category' => null, // Will hold the fetched Category model
    'name' => '',
]);

// Mount function
mount(function (int $id) { // Expecting the ID from the URL
    $this->category = Category::findOrFail($id); // Manually fetch the category
    $this->name = $this->category->name;
});

// Update action
$update = function () {
    $validated = $this->validate([
        'name' => [
            'required',
            'string',
            'min:3',
            Rule::unique('categories')->ignore($this->category->id),
        ],
    ]);

    $this->category->update([
        'name' => $this->name,
        'slug' => Str::slug($this->name),
    ]);

    session()->flash('success', 'Categoría actualizada con éxito.');

    return $this->redirect('/categories');
};

?>
@volt
@extends('adminlte::page')

@section('title', 'Editar Categoría')

@section('content_header')
    <h1>Editar Categoría: {{ $name }}</h1>
@stop

@section('content')
<form wire:submit="update">
    <div class="card">
        <div class="card-body">
            <x-adminlte-input name="name" label="Nombre" placeholder="Escribe el nombre de la categoría" wire:model="name" />
        </div>
        <div class="card-footer">
            <x-adminlte-button class="btn-flat" type="submit" label="Actualizar" theme="success" icon="fas fa-lg fa-save"/>
            <a href="{{ url('categories') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
@stop
@endvolt
