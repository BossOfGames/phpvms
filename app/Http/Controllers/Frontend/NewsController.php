<?php

namespace App\Http\Controllers\Frontend;

use App\Contracts\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(public NewsService $newsService)
    {
    }
    public function index()
    {
        $posts = News::paginate(20);
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
        if (!$post->is_published && (!$post->public && !auth()->check())) {
            abort(404);
        }

        return view('frontend.news.show', [
            'post' => $post,
        ]);
    }
}
