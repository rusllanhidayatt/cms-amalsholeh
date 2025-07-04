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
        $perPage = request()->get('per_page', 9);
        $articles = Article::where('status', 'published')->latest()->paginate($perPage);

        if ($articles->isEmpty()) {
            return response()->json([
                'message' => 'Article Not Found.'
            ], 404);
        }

        return response()->json([
            'data' => $articles->getCollection()->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'title'         => $item->title,
                    'slug'          => $item->slug,
                    'content'       => $item->content,
                    'cover'         => $item->cover,
                    'status'        => $item->status,
                    'created_at'    => $item->created_at,
                    'updated_at'    => $item->updated_at,
                    'tag'           => $item->tag,
                    'category'      => json_decode(json_encode([
                        'category_id' => $item->category_id,
                        'category_title'    => $item->category ? $item->category->title : null,
                        'category_slug'        => $item->category ? $item->category->slug : null
                    ])),
                    'postmeta'      => $item->metas->map(function ($postmeta) use ($item) {
                        return [
                            'post_meta_id'  => $postmeta->id,
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
            'pagination' => [
                'total'         => $articles->total(),
                'count'         => $articles->count(),
                'per_page'      => $articles->perPage(),
                'current_page'  => $articles->currentPage(),
                'last_page'     => $articles->lastPage(),
                'links' => [
                    'path'   => url('/api/v1/articles'),
                    'first'  => $articles->url(1),
                    'last'   => $articles->url($articles->lastPage()),
                    'prev'   => $articles->previousPageUrl(),
                    'next'   => $articles->nextPageUrl(),
                ],
            ],
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
                'status'        => $item->status,
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
        $article = Article::where('slug', $slug)->first();

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
                'password' => bcrypt('default123'),
                'role'     => 'viewer',
            ]);
        }

        $createComment = Comment::create([
            "article_id"    => $article->id,
            "parent_id"     => $user->id,
            "name"          => $user->name,
            "email"         => $user->email,
            "content"       => $request->content,
        ]);

        if (!$createComment) {
            return response()->json([
                'message' => 'Comment Failed!'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Comment Added Successfully.'
            ], 200);
        }
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
        $data = Tag::select('id', 'title', 'slug', 'description', 'created_at', 'updated_at')
            ->get();

        return response()->json([
            'data' => $data->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'title'         => $item->title,
                    'slug'          => $item->slug,
                    'description'   => $item->description,
                    'created_at'    => $item->created_at,
                    'updated_at'    => $item->updated_at,
                ];
            }),
        ]);
    }
}