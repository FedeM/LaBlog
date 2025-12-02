<?php

use App\Models\Category;
use function Livewire\Volt\{state, mount};

// State properties
state(['categories' => []]);

// Mount function
mount(function () {
    $this->categories = Category::latest()->get();
});

// Delete action
$delete = function (int $id) {
    $category = Category::findOrFail($id);
    $category->delete();

    session()->flash('success', 'Categoría eliminada con éxito.');

    return $this->redirect(request()->header('Referer'));
};

?>
@volt

@extends('adminlte::page')

@section('title', 'Listado de Categorías')

@section('content_header')
    <h1>Listado de Categorías</h1>
@stop

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Éxito" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todas las Categorías</h3>
            <div class="card-tools">
                <a href="{{ url('categories/create') }}" class="btn btn-primary btn-sm">Crear Categoría</a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th style="width: 100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td style="width: 100px;">
                                <a href="/categories/{{ $category->id }}/edit" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                                <button class="btn btn-xs btn-default text-danger mx-1" title="Eliminar" wire:click="delete({{ $category->id }})" wire:confirm="¿Estás seguro de que quieres eliminar esta categoría?">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay categorías para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
@endvolt
