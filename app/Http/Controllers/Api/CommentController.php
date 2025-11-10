<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a new comment
     *
     * @param Request $request
     * @param string $type
     * @param int $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $type, $itemId)
    {
        $result = $this->commentService->validateAndCreateComment($request, $type, $itemId);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'errors' => $result['errors'] ?? null
        ], 422);
    }
}