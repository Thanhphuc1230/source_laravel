<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\Slider;
use App\Models\CateProduct;
use App\Models\Product;
use App\Models\News;
use App\Models\Contact;
use App\Models\OrderStatus;
use App\Models\OrderShipping;
use App\Models\OrderProduct;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Get system configuration
     */
    public function getSystem()
    {
        $system = cache()->remember('api_system_config', 3600, function () {
            return System::first();
        });

        if (!$system) {
            return response()->json([
                'success' => false,
                'message' => 'System configuration not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'System configuration retrieved successfully.',
            'data' => [
                'name' => $system->name_vn ?? 'BASE TRAVEL',
                'logo' => $system->logo ? asset($system->logo) : null,
                'favicon' => $system->favicon ? asset($system->favicon) : null,
                'phone' => $system->phone ?? '1800 6700',
                'email' => $system->email ?? 'info@basetravel.com',
                'address' => $system->address ?? 'Lầu 5, Tòa nhà Travel, TP. Hồ Chí Minh',
                'zalo' => $system->zalo ?? 'https://zalo.me/0909090909',
                'facebook' => $system->facebook ?? 'https://facebook.com/basetravel',
                'youtube' => $system->youtube ?? 'https://youtube.com/basetravel',
                'twitter' => $system->twitter ?? 'https://twitter.com/basetravel',
                'instagram' => $system->instagram ?? 'https://instagram.com/basetravel',
                'contact_title' => $system->contact_title_vn ?? 'Kết Nối Với Chúng Tôi',
                'contact_desc' => $system->contact_desc_vn ?? 'Gửi tin nhắn hoặc yêu cầu của bạn, chúng tôi luôn sẵn lòng lắng nghe và hỗ trợ.',
            ]
        ]);
    }

    /**
     * Get active sliders
     */
    public function getSliders()
    {
        $data = cache()->remember('api_sliders', 3600, function () {
            $sliders = Slider::where('status', true)->orderBy('stt', 'asc')->get();
            return $sliders->map(function ($slide) {
                return [
                    'name' => $slide->name_vn,
                    'image' => asset($slide->image),
                    'link' => $slide->link ?? '#'
                ];
            })->toArray();
        });

        return response()->json([
            'success' => true,
            'message' => 'Sliders retrieved successfully.',
            'data' => $data
        ]);
    }

    /**
     * Get tour categories
     */
    public function getCategories()
    {
        $data = cache()->remember('api_categories', 3600, function () {
            $categories = CateProduct::where('status', true)->orderBy('stt', 'asc')->get();
            return $categories->map(function ($cate) {
                return [
                    'id' => $cate->id_cate_product,
                    'name' => $cate->name_vn,
                    'slug' => $cate->slug_vn,
                    'image' => $cate->image_vn ? asset($cate->image_vn) : null
                ];
            })->toArray();
        });

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => $data
        ]);
    }

    /**
     * Get paginated list of tours (with filtering)
     */
    public function getTours(Request $request)
    {
        $query = Product::where('status', true);

        // Filter by category_id
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filter by hot sellers
        if ($request->has('is_hot') && $request->get('is_hot') == 'true') {
            $query->where('hot', true);
        }

        // Filter by keyword search
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('name_vn', 'LIKE', "%{$keyword}%")
                  ->orWhere('name_en', 'LIKE', "%{$keyword}%");
            });
        }

        $paginator = $query->orderBy('stt', 'asc')->paginate(10);

        $tours = collect($paginator->items())->map(function ($product) {
            return [
                'id' => $product->id_product,
                'uuid' => $product->uuid,
                'name' => $product->name_vn,
                'slug' => $product->slug_vn,
                'code' => 'HT-' . $product->id_product,
                'price' => (float) $product->price,
                'price_old' => (float) $product->price_old,
                'image' => asset($product->image),
                'category_name' => $product->cate->name_vn ?? '',
                'is_hot' => (bool) $product->hot
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Tours retrieved successfully.',
            'data' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'tours' => $tours
            ]
        ]);
    }

    /**
     * Get detail of a specific tour by slug
     */
    public function getTourDetail($slug)
    {
        $product = Product::where('slug_vn', $slug)
            ->orWhere('slug_en', $slug)
            ->where('status', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found.',
                'data' => null
            ], 404);
        }

        // Generate dynamic mock specifications for rich Flutter data
        $specs = $this->getMockSpecifications($product->id_product);

        return response()->json([
            'success' => true,
            'message' => 'Tour details retrieved successfully.',
            'data' => [
                'id' => $product->id_product,
                'uuid' => $product->uuid,
                'name' => $product->name_vn,
                'slug' => $product->slug_vn,
                'code' => 'HT-' . $product->id_product,
                'price' => (float) $product->price,
                'price_old' => (float) $product->price_old,
                'image' => asset($product->image),
                'category_name' => $product->cate->name_vn ?? '',
                'intro' => $product->intro_vn,
                'content' => $product->content_vn,
                'specifications' => $specs
            ]
        ]);
    }

    /**
     * Get paginated travel advice news articles
     */
    public function getNews()
    {
        $paginator = News::where('status', true)->orderBy('stt', 'asc')->paginate(10);

        $articles = collect($paginator->items())->map(function ($news) {
            return [
                'name' => $news->name_vn,
                'slug' => $news->slug_vn,
                'image' => asset($news->image),
                'intro' => $news->intro_vn,
                'created_at' => $news->created_at ? $news->created_at->format('d-m-Y') : ''
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'News articles retrieved successfully.',
            'data' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'articles' => $articles
            ]
        ]);
    }

    /**
     * Get detail of a specific news article
     */
    public function getNewsDetail($slug)
    {
        $news = News::where('slug_vn', $slug)
            ->orWhere('slug_en', $slug)
            ->where('status', true)
            ->first();

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.',
                'data' => null
            ], 404);
        }

        // Increment view count safely
        $news->increment('views');

        return response()->json([
            'success' => true,
            'message' => 'Article details retrieved successfully.',
            'data' => [
                'name' => $news->name_vn,
                'slug' => $news->slug_vn,
                'image' => asset($news->image),
                'intro' => $news->intro_vn,
                'content' => $news->content_vn,
                'created_at' => $news->created_at ? $news->created_at->format('d-m-Y') : '',
                'views' => $news->views
            ]
        ]);
    }

    /**
     * Checkout pipeline for mobile app orders (Stateless)
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name_order' => 'required|string|max:100',
            'l_name_order' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'address' => 'required|string',
            'payment_method' => 'required|string|in:cash,card,transfer',
            'items' => 'required|array|min:1',
            'items.*.id_product' => 'required|integer|exists:tp_products,id_product',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                // Calculate order totals and validate items
                $totalPrice = 0;
                $validatedItems = [];

                foreach ($request->input('items') as $item) {
                    $product = Product::find($item['id_product']);
                    if (!$product || !$product->status) {
                        throw new \Exception("Sản phẩm ID {$item['id_product']} không tồn tại hoặc đã ngừng kinh doanh.");
                    }
                    $subtotal = $product->price * $item['quantity'];
                    $totalPrice += $subtotal;
                    
                    $validatedItems[] = [
                        'product' => $product,
                        'qty' => $item['quantity'],
                        'price' => $product->price
                    ];
                }

                // Create Order Shipping
                $shipping = OrderShipping::create([
                    'f_name_order' => $request->input('f_name_order'),
                    'l_name_order' => $request->input('l_name_order'),
                    'uuid_order_shipping' => (string) Str::uuid(),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'note' => $request->input('note'),
                ]);

                // Create Order Status
                $orderStatus = OrderStatus::create([
                    'uuid_order_status' => (string) Str::uuid(),
                    'shipping_id' => $shipping->id_order_shipping,
                    'payment_method' => $request->input('payment_method'),
                    'total' => $totalPrice,
                ]);

                // Create Order Products
                foreach ($validatedItems as $item) {
                    OrderProduct::create([
                        'uuid_order_product' => (string) Str::uuid(),
                        'order_status_id' => $orderStatus->id_order_status,
                        'product_id' => $item['product']->id_product,
                        'quantity' => $item['qty'],
                        'price' => $item['price']
                    ]);
                }

                // Return details
                $responseData = [
                    'order_id' => $orderStatus->id_order_status,
                    'total_price' => (float) $totalPrice,
                    'payment_method' => $orderStatus->payment_method
                ];

                if ($orderStatus->payment_method === 'transfer') {
                    $responseData['transfer_details'] = [
                        'bank_name' => 'Vietcombank',
                        'account_number' => '1014567890',
                        'account_name' => 'CONG TY TNHH BASE TRAVEL',
                        'content' => 'HT' . $orderStatus->id_order_status
                    ];
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công.',
                    'data' => $responseData
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đặt hàng thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Contact submission form
     */
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Contact::create([
                'uuid' => (string) Str::uuid(),
                'fullname' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'subject' => 'Liên hệ từ Mobile App',
                'message' => $request->input('message'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ý kiến đóng góp của bạn đã được ghi nhận thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi phản hồi thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail of a static page
     */
    public function getPageDetail($id_page)
    {
        $data = cache()->remember("api_page_{$id_page}", 3600, function () use ($id_page) {
            $page = Page::where('id_page', $id_page)->first();
            if (!$page) return null;
            return [
                'id' => $page->id_page,
                'uuid' => $page->uuid,
                'name' => $page->name_vn,
                'slug' => $page->slug_vn,
                'content' => $page->content_vn,
                'image' => $page->image_vn ? asset($page->image_vn) : null
            ];
        });

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Page details retrieved successfully.',
            'data' => $data
        ]);
    }

    /**
     * Helper to mock rich specifications
     */
    private function getMockSpecifications($id)
    {
        $specs = [
            1 => [
                'departure' => 'Hà Nội',
                'duration' => '2 Ngày 1 Đêm',
                'date' => 'Hàng ngày',
                'schedule' => 'Hà Nội - Hạ Long - Vịnh Lan Hạ'
            ],
            2 => [
                'departure' => 'Hà Nội',
                'duration' => '3 Ngày 2 Đêm',
                'date' => 'Thứ 6 hàng tuần',
                'schedule' => 'Hà Nội - Hà Giang - Lộc Cú - Đồng Văn'
            ],
            5 => [
                'departure' => 'TP. Hồ Chí Minh',
                'duration' => '6 Ngày 5 Đêm',
                'date' => 'Thứ 4 & Thứ 7 hàng tuần',
                'schedule' => 'TP. HCM - Tokyo - Fuji - Kyoto - Osaka'
            ],
            6 => [
                'departure' => 'TP. Hồ Chí Minh',
                'duration' => '9 Ngày 8 Đêm',
                'date' => 'Hàng tháng',
                'schedule' => 'TP. HCM - Paris - Geneva - Venice - Rome'
            ]
        ];

        return $specs[$id] ?? [
            'departure' => 'Toàn quốc',
            'duration' => 'Hành trình trọn gói',
            'date' => 'Liên hệ tư vấn',
            'schedule' => 'Lịch trình linh hoạt cao cấp'
        ];
    }
}
