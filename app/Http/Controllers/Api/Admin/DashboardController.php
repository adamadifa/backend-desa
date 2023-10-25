<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $categories = Category::count();
        $posts = Post::count();
        $products = Product::count();
        $aparaturs = Aparatur::count();

        return response()->json(
            [
                'success' => true,
                'message' => 'Rekap Data Dashboard',
                'data' => [
                    'categories' => $categories,
                    'posts' => $posts,
                    'products' => $products,
                    'aparaturs' => $aparaturs
                ]
            ]
        );
    }
}
