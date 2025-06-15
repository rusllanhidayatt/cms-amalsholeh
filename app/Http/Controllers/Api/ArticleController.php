<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use App\Models\PostMeta;

class ArticleController extends Controller
{

    public function showArticles() 
    {
        
        $articles = Article::with(['category', 'metas'])->get();

        if ($articles->isEmpty()) {
            return response()->json([
                'message' => 'Article Not Found.'
            ], 404);
        }

        return response()->json([
            'data' => $articles->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'title'         => $item->title,
                    'slug'          => $item->slug,
                    'content'       => $item->content,
                    'cover'         => $item->cover,
                    'created_at'    => $item->created_at,
                    'updated_at'    => $item->updated_at,
                    'category_id'   => $item->category_id,
                    'category'      => $item->category ? $item->category->title : null,
                    'postmeta'      => $item->metas->map(function ($postmeta) use ($item) {
                        return [
                            'id'            => $postmeta->id,
                            'article_id'    => $postmeta->article_id,
                            'article_name'  => $item->title,
                            'article_slug'  => $item->slug,
                            'key'           => $postmeta->key,
                            'value'         => $postmeta->value,
                            'created_at'    => $postmeta->created_at,
                            'updated_at'    => $postmeta->updated_at,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function showBySlug($slug)
    {
        $item = Article::with('category')
            ->where('slug', $slug)
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Article Not Found.'
            ], 404);
        }

        return response()->json([
            'data' => [
                'id'            => $item->id,
                'title'         => $item->title,
                'slug'          => $item->slug,
                'content'       => $item->content,
                'cover'         => $item->cover,
                'created_at'    => $item->created_at,
                'updated_at'    => $item->updated_at,
                'category_id'   => $item->category_id,
                'category'      => $item->category ? $item->category->title : null,
            ]
        ]);
    }

    public function showcomments($slug)
    {
        $item = Article::with('comments')
            ->where('slug', $slug)
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Comments on this Article Not found.'
            ], 404);
        }

        return response()->json([
            'article_id' => $item->id,
            'slug'       => $item->slug,
            'title'      => $item->title,
            'comment'   => $item->comments->map(function ($comment) {
                return [
                    'id'         => $comment->id,
                    'name'       => $comment->name,
                    'email'      => $comment->email,
                    'content'    => $comment->content,
                    'parent_id'  => $comment->parent_id,
                    'created_at' => $comment->created_at,
                    'update_at'  => $comment->updated_at,
                ];
            }),
        ]);
    }

    public function postcomment(Request $request, $slug)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        $artilce = Article::where('slug', $slug)->first();

        if (!$article) {
            return response()->json([
                'message' => 'Sorry , incorrect to comment.'
            ], 404);
        }

        // Cari atau buat user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => bcrypt('default123'), // Password default jika perlu
            ]);
        }

        $comment = new Comment();
        $comment->article_id = $article->id;
        $comment->parent_id  = $user->id;
        $comment->name       = $request->name;
        $comment->email      = $request->email;
        $comment->content    = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Comment Added Successfully.',
            'data' => [
                'id'         => $comment->id,
                'parent_id'  => $comment->parent_id,
                'name'       => $comment->name,
                'email'      => $comment->email,
                'content'    => $comment->content,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
            ]
        ], 201);
    }

    public function showCategories() 
    {
        $data = Category::select('id', 'title', 'slug', 'description', 'icon', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'data' => $data->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'title'         => $item->title,
                    'slug'          => $item->slug,
                    'description'   => $item->description,
                    'icon'          => $item->icon,
                    'created_at'    => $item->created_at,
                    'updated_at'    => $item->updated_at,
                ];
            }),
        ]);
    }

    public function showTags() 
    {
        $data = Tag::select('id', 'title', 'slug', 'description', 'icon', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'data' => $data->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'title'         => $item->title,
                    'slug'          => $item->slug,
                    'description'   => $item->description,
                    'icon'          => $item->icon,
                    'created_at'    => $item->created_at,
                    'updated_at'    => $item->updated_at,
                ];
            }),
        ]);
    }
}