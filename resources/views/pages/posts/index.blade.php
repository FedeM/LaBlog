<?php

use App\Models\Post;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, mount};

// State properties
state(['posts' => []]);

// Mount function
mount(function () {
    // Eager load the user relationship to avoid N+1 query issues
    $this->posts = Post::with('user')->latest()->get();
});

// Delete action
$delete = function (int $id) {
    $post = Post::findOrFail($id);
    $post->delete();

    session()->flash('success', 'Post eliminado con éxito.');

    return $this->redirect(request()->header('Referer'));
};

?>
@extends('adminlte::page')

@section('title', 'Listado de Posts')

@section('content_header')
    <h1>Listado de Posts</h1>
@stop

@section('content')
    <div>
    @volt
        <div>
            @if(session('success'))
                <x-adminlte-alert theme="success" title="Éxito" dismissable>
                    {{ session('success') }}
                </x-adminlte-alert>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Todos los Posts</h3>
                    <div class="card-tools">
                        <a href="{{ url('posts/create') }}" class="btn btn-primary btn-sm">Crear Post</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Fecha de Creación</th>
                                <th style="width: 100px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user->name ?? 'N/A' }}</td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="/posts/{{ $post->id }}/edit" class="btn btn-xs btn-default text-primary mx-1" title="Editar">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </a>
                                        <button class="btn btn-xs btn-default text-danger mx-1" title="Eliminar" wire:click="delete({{ $post->id }})" wire:confirm="¿Estás seguro de que quieres eliminar este post?">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay posts para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endvolt
    </div>
@stop