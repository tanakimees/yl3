<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <ul class="w-full bg-white shadow-sm sm:rounded-lg dark:bg-gray-700 mt-5 p-3 h-auto">
                <h1 class="text-white uppercase text-2xl">{{ $post->title }}</h1>
                <h1 class="text-white overflow-hidden">{{ $post->description }}</h1>
                <p class="text-gray-500">Published: {{ $post->created_at->format('d F Y') }}</p>

                <h2 class="text-white mt-4">Comments</h2>

                @if ($comments->count() > 0)
                <ul>
                    @foreach ($comments as $comment)
                    @if($comment->post_id == $post->id)
                    <li class="mt-4">
                        <p class="text-green-500">{{ $comment->poster }}</p>
                        <p class="text-green-500 text-md">{{ $comment->text }}</p>
                        <p class="text-white text-xs">{{ $comment->created_at->format('d F Y') }}</p>
                        @if($user->type == 'admin')
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button text-black dark:text-white bg-red-500 mt-2" onclick="return confirm('Are you sure you want to delete this comment?')">Delete Comment  {{ $comment->id }}</button>
                        </form>
                        @endif
                    </li>
                    @endif
                    @endforeach
                </ul>
                @else
                <p class="text-gray-600">No comments found for this post.</p>
                @endif
            </ul>

            @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="text-white" for="comment">Comment:</label>
                    <textarea class="text" id="text" name="text" rows="3"></textarea>
                    @error('comment')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary text-white bg-green-500">Submit Comment</button>
            </form>
            @else
            <p>Please sign in to leave a comment.</p>
            @endauth

            @if($user->type == 'admin')
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="button text-black dark:text-white bg-red-500 mt-2" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</button>
            </form>
            @endif
        </div>
    </div>
    </div>
</x-app-layout>