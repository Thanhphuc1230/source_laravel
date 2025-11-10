<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Handle search request
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $data = $this->searchService->handleSearchRequest($request);

        // Check if should redirect (empty search)
        if (isset($data['should_redirect']) && $data['should_redirect']) {
            return redirect()->route($data['redirect_route']);
        }

        return view('frontend.modules.search.results', $data);
    }

    /**
     * Get search suggestions (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        $data = $this->searchService->getSearchSuggestionsForAjax($request);
        return response()->json($data);
    }
}