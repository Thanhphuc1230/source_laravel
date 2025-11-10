<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();

        // Get user's comments with post information
        $comments = Comment::select('tp_comments.*', 'news.slug as news_slug', 'products.slug as product_slug', 'pages.slug as page_slug')
            ->leftJoin('tp_news as news', function($join) {
                $join->on('tp_comments.id_post', '=', 'news.id_new')
                     ->where('tp_comments.type_post', '=', 1);
            })
            ->leftJoin('tp_products as products', function($join) {
                $join->on('tp_comments.id_post', '=', 'products.id_product')
                     ->where('tp_comments.type_post', '=', 2);
            })
            ->leftJoin('tp_pages as pages', function($join) {
                $join->on('tp_comments.id_post', '=', 'pages.id_page')
                     ->where('tp_comments.type_post', '=', 3);
            })
            ->where('tp_comments.email', $user->email)
            ->orderBy('tp_comments.created_at', 'desc')
            ->paginate(10);

        return view('frontend.modules.auth.profile', compact('user', 'comments'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('password_error', 'Mật khẩu hiện tại không đúng!');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('password_success', 'Mật khẩu đã được thay đổi thành công!');
    }
}