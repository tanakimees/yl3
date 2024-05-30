<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\post;
use App\Models\comment;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'type' => 'default',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function commentAdd(Request $request, post $post, comment $comment)
    {
        $validatedData = $request->validate([
            'text' => 'required|string|max:255',
          ]);

        $comment = new comment([
            'poster' => Auth::id(), // Get currently logged-in user ID
            'post_id' => $post->id,
            'text' => $validatedData['text'],
        ]);

        $comment->save();

        return redirect()->route('post', $post->id)->with('success', 'Comment submitted successfully!');
    }

    public function postAdd(Request $request, post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $post = new Post([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $post->save();

        return redirect()->route('dashboard')->with('success', 'Post created successfully!');
    }

    public function postDelete(post $post)
    {
        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully!');
    }
}
