<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, mount};
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


// State properties
state([
    'title' => '',
    'content' => '',
    'selectedCategories' => [], // Keep to avoid errors if referenced, but won't be used
    'selectedTags' => [],     // Keep to avoid errors if referenced, but won't be used
    // 'allCategories' => [], // Commented out to simplify state
    // 'allTags' => [],     // Commented out to simplify state
]);

// Mount function
mount(function () {
    // $this->allCategories = Category::all(); // Commented out
    // $this->allTags = Tag::all();         // Commented out
});

// Save action
$save = function () {
    // ALL VALIDATION COMMENTED OUT
    // $validated = $this->validate([
    //     'title' => ['required', 'string', 'min:5', 'unique:posts,title'],
    //     'content' => ['required', 'string', 'min:20'],
    //     'selectedCategories' => ['required', 'array'],
    //     'selectedTags' => ['required', 'array'],
    // ]);

    $post = Post::create([
        'title' => $this->title,
        'slug' => Str::slug($this->title),
        'content' => $this->content ?: 'Default content if empty', // Provide default if content is null
        'user_id' => Auth::user()->id, // Using Auth facade directly
    ]);

    // RELATIONSHIPS COMMENTED OUT
    // $post->categories()->sync($this->selectedCategories);
    // $post->tags()->sync($this->selectedTags);

    session()->flash('success', 'Post creado con éxito.');

    return $this->redirect('/posts');
};

?>
@volt
@extends('adminlte::page')

@section('title', 'Crear Nuevo Post')

@section('content_header')
    <h1>Crear Nuevo Post</h1>
@stop

@section('content')
<form wire:submit="save">
    <div class="card">
        <div class="card-body">
            {{-- Title --}}
            <input type="text" wire:model="title" placeholder="Escribe el título del post" class="form-control mb-3">

            {{-- Content --}}
            <div class="form-group">
                <label for="content">Contenido</label>
                <textarea id="content" wire:model="content" class="form-control" rows="8" placeholder="Escribe el contenido del post"></textarea>
            </div>

            {{-- Categories --}}
            {{-- <x-adminlte-select2 id="categories" name="selectedCategories" label="Categorías" igroup-size="md" :config="['placeholder' => 'Selecciona categorías...']" multiple wire:model="selectedCategories">
                <x-slot name="prependSlot">
                    <div class="input-group-text bg-gradient-info">
                        <i class="fas fa-tags"></i>
                    </div>
                </x-slot>
                @foreach($allCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-adminlte-select2> --}}

            {{-- Tags --}}
            {{-- <x-adminlte-select2 id="tags" name="selectedTags" label="Etiquetas" igroup-size="md" :config="['placeholder' => 'Selecciona etiquetas...']" multiple wire:model="selectedTags">
                <x-slot name="prependSlot">
                    <div class="input-group-text bg-gradient-purple">
                        <i class="fas fa-hashtag"></i>
                    </div>
                </x-slot>
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </x-adminlte-select2> --}}

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar Post Simple</button>
            <a href="{{ url('posts') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
</form>
@stop
@endvolt
