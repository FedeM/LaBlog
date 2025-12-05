<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use function Livewire\Volt\{state, mount};

// State properties
state([
    'post' => null, // Will hold the fetched Post model
    'title' => '',
    'content' => '',
    'selectedCategories' => [],
    'selectedTags' => [],
    'allCategories' => [],
    'allTags' => [],
]);

// Mount function
mount(function (int $id) { // Expecting the ID from the URL
    $this->post = Post::with(['categories', 'tags'])->findOrFail($id); // Manually fetch the post with relationships
    $this->title = $this->post->title;
    $this->content = $this->post->content;
    $this->selectedCategories = $this->post->categories->pluck('id')->toArray();
    $this->selectedTags = $this->post->tags->pluck('id')->toArray();

    $this->allCategories = Category::all();
    $this->allTags = Tag::all();
});

// Update action
$update = function () {
    $validated = $this->validate([
        'title' => [
            'required',
            'string',
            'min:5',
            Rule::unique('posts')->ignore($this->post->id),
        ],
        'content' => ['required', 'string', 'min:20'],
        'selectedCategories' => ['required', 'array'],
        'selectedTags' => ['required', 'array'],
    ]);

    $this->post->update([
        'title' => $this->title,
        'slug' => Str::slug($this->title),
        'content' => $this->content,
        // user_id should not be updated on edit
    ]);

    $this->post->categories()->sync($this->selectedCategories);
    $this->post->tags()->sync($this->selectedTags);

    session()->flash('success', 'Post actualizado con éxito.');

    return $this->redirect('/posts');
};

?>

@extends('adminlte::page')

@section('title', 'Editar Post')

@section('content_header')
<div>
    @volt
    <h1>Editar Post: {{ $title }}</h1>
    @endvolt
</div>
@stop

@section('content')
<div>
    @volt
    <form wire:submit="update">
        <div class="card">
            <div class="card-body">
                {{-- Title --}}
                <x-adminlte-input name="title" label="Título" placeholder="Escribe el título del post" wire:model="title" />

                {{-- Content --}}
                @php
                $config = [
                    "height" => "300",
                ];
                @endphp
                <x-adminlte-text-editor name="content" label="Contenido" igroup-size="sm" :config="$config" wire:model="content"/>

                {{-- Categories --}}
                <x-adminlte-select2 id="categories" name="selectedCategories" label="Categorías" igroup-size="md" :config="['placeholder' => 'Selecciona categorías...']" multiple wire:model="selectedCategories">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-info">
                            <i class="fas fa-tags"></i>
                        </div>
                    </x-slot>
                    @foreach($allCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-adminlte-select2>

                {{-- Tags --}}
                <x-adminlte-select2 id="tags" name="selectedTags" label="Etiquetas" igroup-size="md" :config="['placeholder' => 'Selecciona etiquetas...']" multiple wire:model="selectedTags">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-purple">
                            <i class="fas fa-hashtag"></i>
                        </div>
                    </x-slot>
                    @foreach($allTags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </x-adminlte-select2>

            </div>
            <div class="card-footer">
                <x-adminlte-button class="btn-flat" type="submit" label="Actualizar Post" theme="success" icon="fas fa-lg fa-save"/>
                <a href="{{ url('posts') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
    @endvolt
</div>
@stop