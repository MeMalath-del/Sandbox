<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()->with(['author', 'category', 'tags']);

        if ($request->category) {
            $category = BlogCategory::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $posts = $query->latest()->paginate(12);
        $categories = BlogCategory::withCount('posts')->get();
        $popularPosts = BlogPost::published()->orderByDesc('views_count')->limit(5)->get();
        $tags = \App\Models\BlogTag::withCount('posts')->orderByDesc('posts_count')->limit(20)->get();

        return view('blog.index', compact('posts', 'categories', 'popularPosts', 'tags'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->increment('views_count');

        $comments = $post->comments()
            ->whereNull('parent_id')
            ->approved()
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(4)
            ->get();

        return view('blog.show', compact('post', 'comments', 'relatedPosts'));
    }

    public function comment(Request $request, BlogPost $post)
    {
        $request->validate([
            'content' => 'required|string|min:5|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        BlogComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'status' => auth()->user()->isAdmin() ? 'approved' : 'pending',
        ]);

        return back()->with('success', 'تم إرسال تعليقك');
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()->published()->latest()->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag($slug)
    {
        $tag = \App\Models\BlogTag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()->published()->latest()->paginate(12);

        return view('blog.tag', compact('tag', 'posts'));
    }
}
