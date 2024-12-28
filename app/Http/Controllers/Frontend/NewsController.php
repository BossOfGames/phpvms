<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Models\News;
use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(public NewsService $newsService)
    {
    }

    public function index()
    {
        // if the user is not logged in, only show public posts
        $postsQuery = News::where('visible', true)->orderBy('created_at', 'desc');

        if (!auth()->check()) {
            $postsQuery->where('public', true);
        }

        $posts = $postsQuery->paginate(10);

        return view('frontend.news.index', [
            'posts' => $posts,
        ]);
    }

    public function show($id)
    {
        $post = News::find($id);
        if (!$post) {
            abort(404);
        }
        // If the post is not published, or if the post is private and the user is not logged in, abort
        if (!$post->visible && (!$post->public && !auth()->check())) {
            abort(404);
        }

        return view('frontend.news.show', [
            'post' => $post,
        ]);
    }
}
