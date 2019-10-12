<?php

namespace App;

/**
 *
 */
trait LikeTrait
{
  public function likes()
  {
    return $this->morphMany('App\Like','likeable');
  }

  public function youLikeIt()
  {
    $like = New Like();
    $like->user_id = auth()->user()->id;

    $this->likes()->save($like);

    return $like;
  }

  public function youLiked()
  {
    return !! $this->likes()->where('user_id', auth()->id())->count();
  }

  public function youUnliked()
  {
    $this->likes()->where('user_id', auth()->id())->delete();
  }
}
