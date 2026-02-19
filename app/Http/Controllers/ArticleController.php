<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $title = 'Articles';
        // Gunakan paginate() agar tombol "Next/Prev" di bawah berfungsi
        // Nama variabel disamakan dengan yang ada di Blade: $articles
        $articles = Article::where('status', 'published')
                            ->latest()
                            ->paginate(10); // Ambil 10 artikel per halaman

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