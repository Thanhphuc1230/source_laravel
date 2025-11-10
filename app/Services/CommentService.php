<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get approved comments for an item (news/product)
     *
     * @param string $type
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprovedCommentsForItem($type, $itemId)
    {
        // Cache approved comments
        return \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['comments'] ?? 'comments',
            "approved_comments_{$type}_{$itemId}",
            \App\Services\CacheService::getTtl('medium'),
            fn () => $this->commentRepository->getApprovedCommentsForItem($type, $itemId)
        );
    }

    /**
     * Create a new comment
     *
     * @param array $data
     * @return array
     */
    public function createComment(array $data)
    {
        // Validate data
        $validator = Validator::make($data, [
            'type' => 'required|in:news,product',
            'item_id' => 'required|integer',
            'content' => 'required|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'nullable|integer|min:1|max:5', // Rating chỉ cho product
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ];
        }

        try {
            // Convert type to type_post number
            $data['type_post'] = $data['type'] === 'news' ? 1 : 2;
            $data['id_post'] = $data['item_id'];

            // Remove new format fields
            unset($data['type'], $data['item_id']);

            // Set default status to 0 (pending approval)
            $data['status'] = 0;

            $comment = $this->commentRepository->createComment($data);

            return [
                'success' => true,
                'message' => 'Bình luận của bạn đã được gửi và đang chờ duyệt',
                'comment' => $comment
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi bình luận'
            ];
        }
    }

    /**
     * Validate comment data from request
     *
     * @param Request $request
     * @param string $type
     * @param int $itemId
     * @return array
     */
    public function validateAndCreateComment(Request $request, $type, $itemId)
    {
        $data = [
            'type' => $type,
            'item_id' => $itemId,
            'content' => $request->input('content_vn'), // Map content_vn to content
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rating' => $type === 'product' ? $request->input('rating') : null, // Rating chỉ cho product
        ];

        return $this->createComment($data);
    }
}