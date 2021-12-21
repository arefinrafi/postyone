<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->only(['store', 'destroy']);
    }

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->with('user', 'likes')->paginate(20);
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
        // return view('posts.show', [
        //     'post' => $post
        // ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $request->user()->posts()->create([
            'body' => $request->body,
        ]);

        return back();
    }

    public function destroy(Post $post)
    {
        if (!$post->ownedby(auth()->user())) {
            dd('no');
        }
        $post->delete();
        return back();
    }
}
