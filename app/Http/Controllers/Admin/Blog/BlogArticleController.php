<?php

namespace App\Http\Controllers\Admin\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogArticleController extends Controller
{
    public function index()
    {

        return view('admin.blog.articles.index');
    }
     public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:2048', // 2MB
        ]);

        $path = $request->file('upload')->store('ckeditor', 'public');
        $url  = Storage::disk('public')->url($path);

        // CKEditor expects: { "url": "..." }
        return response()->json(['url' => $url]);
    }

}
