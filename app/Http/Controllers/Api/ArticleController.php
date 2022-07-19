<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $article = Article::latest()->get();
        return response()->json([ArticleResource::collection($article), 'article created']);
    }
    public function show($id)
    {
        $data = Article::find($id);
        if (is_null($data)) {
            return response()->json(['Data not Found', 404]);
        }
        return response()->json([new ArticleResource($data)]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $article = Article::create([
            'title' => $request->title,
            'description' => $request->description
        ]);
        return response()->json(['Article added', new ArticleResource($article)]);
    }
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $article->title = $request->title;
        $article->description = $request->description;
        $article->save();

        return response()->json(['Article updated successfully.', new ArticleResource($article)]);
    }
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(['Article deleted.']);
    }
}
