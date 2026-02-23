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

        $categories = Article::where('status', 'published')
                              ->distinct()
                              ->pluck('category')
                              ->filter()
                              ->sort()
                              ->values();

        return view('articles.index', compact('articles', 'title', 'categories'));
    }

    public function show(Article $article)
    {
        $title = $article->title;
        
        $relatedArticles = Article::where('id', '!=', $article->id)
                                    ->where('status', 'published')
                                    ->where('category', $article->category)
                                    ->latest()
                                    ->limit(3)
                                    ->get();

        return view('articles.show', compact('article', 'relatedArticles', 'title'));
    }
}