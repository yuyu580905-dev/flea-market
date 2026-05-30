<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'comment' => $request->comment,
        ]);

        // user.profile を読み込む
        $comment->load('user.profile');

        return response()->json([
            'user_name' => $comment->user->name,
            'user_image' => $comment->user->profile?->profile_image,
            'comment' => $comment->comment,
            'comments_count' => $item->comments()->count(),
        ]);
    }
}
