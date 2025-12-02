<?php

use App\Models\Tag;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, mount};


// State properties
state(['tags' => []]);

// Mount function
mount(function () {
    $this->tags = Tag::latest()->get();
});

// Delete action
$delete = function (int $id) {
    $tag = Tag::findOrFail($id);
    $tag->delete();

    session()->flash('success', 'Etiqueta eliminada con éxito.');

    return $this->redirect(request()->header('Referer'));
};

?>
@volt

@extends('adminlte::page')

@section('title', 'Listado de Etiquetas')

@section('content_header')
    <h1>Listado de Etiquetas</h1>
@stop

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Éxito" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Todas las Etiquetas</h3>
            <div class="card-tools">
                <a href="{{ url('tags/create') }}" class="btn btn-primary btn-sm">Crear Etiqueta</a>
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
                    @forelse ($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td>{{ $tag->name }}</td>
                            <td>{{ $tag->slug }}</td>
                            <td style="width: 100px;">
                                <a href="/tags/{{ $tag->id }}/edit" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                                <button class="btn btn-xs btn-default text-danger mx-1" title="Eliminar" wire:click="delete({{ $tag->id }})" wire:confirm="¿Estás seguro de que quieres eliminar esta etiqueta?">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay etiquetas para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
@endvolt