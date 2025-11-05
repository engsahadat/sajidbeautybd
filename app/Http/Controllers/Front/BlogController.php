<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('author')->where('status', 'active')->orderBy('created_at', 'desc')->paginate(10);
        return view('front-end.blog.index', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::with('author')->where('id', $id)->firstOrFail();
        return view('front-end.blog.show', compact('blog'));
    }
}
