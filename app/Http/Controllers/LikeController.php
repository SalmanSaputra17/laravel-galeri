<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Input;

class LikeController extends Controller
{
    public function postLike()
    {
      $postId = Input::get('postId');
      $post = Post::find($postId);

      if ( !$post->youLiked() ) {
        $post->youLikeIt();
        return response()->json(['status' => 'success', 'message' => 'Liked']);
      }else{
        $post->youUnliked();
        return response()->json(['status' => 'success', 'message' => 'Unliked']);
      }
    }
}
