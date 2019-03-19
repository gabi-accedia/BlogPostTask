<?php

namespace App\Models\Eloquent;

use App\Post as Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostsService
{
    public static function store($userId, $postDescription, $postTitle, $isHeadline){
        $newPost = Post::create(array(
            'user_id' => $userId,
            'description' => $postDescription,
            'title' => $postTitle,
            'is_headline' => $isHeadline
        ));

        return $newPost;
    }

    public static function headlineValidation($userId, $isHeadline)
    {
        $today = Carbon::today();
        $isValid = true;

        if ($isHeadline) {
            $currentHeadLinePosts = DB::table('posts')
                ->whereDate('created_at', $today)
                ->where('user_id', $userId)
                ->where('is_headline', true)
                ->get();

            if (count($currentHeadLinePosts) >= 2) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}
