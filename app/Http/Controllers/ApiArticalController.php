<?php
namespace App\Http\Controllers;
use File;
use App\Artical;
use App\Authorizable;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Common\FileUploadComponent;
use Illuminate\Support\Facades\Storage;

class ApiArticleController extends Controller
{
    public function index()
    {
        return Article::all();
    }
    public function show(Article $article)
    {
        return $article;
    }
    public function store(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 201);
    }
    public function update(Request $request, Article $article)
    {
        $article->update($request->all());
        return response()->json($article, 200);
    }
    public function delete(Article $article)
    {
        $article->delete();
        return response()->json(null, 204);
    }
}