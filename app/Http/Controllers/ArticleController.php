<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Articles';

        $query = Article::where('status', 'published')->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->paginate(10);

        return view('articles.index', compact('articles', 'title'));
    }

    public function show($slug)
    {
        
        $article = Article::where('slug', $slug)->firstOrFail();
        $title = $article->title ;
        
        // Ambil artikel terkait, pastikan hanya yang sudah dipublikasikan
        $relatedArticles = Article::where('id', '!=', $article->id)
                                    ->where('status', 'published')
                                    ->limit(3)
                                    ->get();

        return view('articles.show', compact('article', 'relatedArticles', 'title'));
    }
}