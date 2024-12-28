<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Controller;
use App\Http\Requests\CreatePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\News;
use App\Repositories\NewsRepository;
use App\Repositories\PageRepository;
use App\Services\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

class NewsController extends Controller
{
    /**
     * @param NewsService $newsService
     * @param NewsRepository $newsRepo
     */
    public function __construct(
        public NewsService $newsService,
        private readonly NewsRepository $newsRepo
    ) {
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $posts = News::paginate(20);

        return view('admin.news.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new Airlines.
     */
    public function create(): View
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created Airlines in storage.
     *
     * @param \App\Http\Requests\CreatePageRequest $request
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     *
     * @return RedirectResponse
     */
    public function store(CreatePageRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->newsService->addNews($input);

        Flash::success('News saved successfully.');
        return redirect(route('admin.news.index'));
    }

    /**
     * Display the specified news post.
     *
     * @param int $id
     *
     * @return View
     */
    public function show(int $id): View
    {
        $post = $this->newsRepo->findWithoutFail($id);

        return view('admin.news.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified pages
     *
     * @param int $id
     *
     * @return RedirectResponse|View
     */
    public function edit(int $id): RedirectResponse|View
    {
        $page = $this->newsRepo->findWithoutFail($id);

        if (empty($page)) {
            Flash::error('Page not found');
            return redirect(route('admin.news.index'));
        }

        return view('admin.news.edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified Airlines in storage.
     *
     * @param int               $id
     * @param UpdatePageRequest $request
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     *
     * @return RedirectResponse
     */
    public function update(int $id, UpdatePageRequest $request): RedirectResponse
    {
        $page = $this->newsRepo->findWithoutFail($id);

        if (empty($page)) {
            Flash::error('news not found');
            return redirect(route('admin.news.index'));
        }

        $this->newsService->updateNews($request->all());

        Flash::success('news updated successfully.');
        return redirect(route('admin.news.index'));
    }

    /**
     * Remove the specified Airlines from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $pages = $this->newsRepo->findWithoutFail($id);

        if (empty($pages)) {
            Flash::error('News post not found');
            return redirect(route('admin.news.index'));
        }

        $this->newsRepo->delete($id);

        Flash::success('News post deleted successfully.');
        return redirect(route('admin.news.index'));
    }
}
