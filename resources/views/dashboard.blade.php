<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div>
                @if ($posts->count() > 0)
                @foreach ($posts as $post)
                <ul class="w-full bg-white shadow-sm sm:rounded-lg dark:bg-gray-700 mt-5 p-3 h-auto">
                    <li class="text-gray-400 overflow-hidden h-auto">
                        <a href="{{ route('post', $post->id) }}">
                            <h2 class="text-white uppercase text-2xl underline">{{ $post->title }}</h2>
                        </a>
                        {{ $post->description }}
                        <div class="flex justify-end">
                            <p class="text-gray-500">Published: {{ $post->created_at->format('d F Y') }}</p>
                        </div>
                    </li>
                </ul>
                @endforeach
                @else
                <p>No published posts found.</p>
                @endif
            </div>

            @if($user->type == 'admin')
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="mt-3 flex flex-col">
                @csrf
                <div class="form-group flex justify-end">
                    <label class="text-black dark:text-white" for="title">Title:</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group flex justify-end mt-1">
                    <label class="text-black dark:text-white" for="description">Description:</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="flex justify-end mt-2">
                    <button type="submit" class="btn btn-primary text-black dark:text-white bg-green-500">Create Post</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>