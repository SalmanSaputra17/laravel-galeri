@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, {{ Auth::user()->name }}</div>
            </div>
            <a href="#myModal" data-toggle="modal" class="btn btn-primary btn-block"><i class="fa fa-upload"></i> Upload Image</a>
        </div>
    </div>

    <br/>

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
              My Gallery
            </div>
        </div>
      </div>

      @foreach( $posts as $post )
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="show_image">
                <a href="#{{ $post->id }}" data-toggle="modal">
                  <img src="{{ asset('images/'.$post->image) }}">
                </a>
              </div>
            </div>
            <div class="post-footer">
              <div class="button-footer">
                <a href="#{{ $post->id }}" data-toggle="modal" class="btn btn-default">
                  <i class="fa fa-comment"> Comment</i>
                </a>
                <span>{{ $post->comments->count() }}</span>
                <span class="btn btn-default {{ $post->youLiked() ? "Liked" : "" }}" onclick="postLiked('{{ $post->id }}', this)">
                  <i class="fa fa-thumbs-up"> Like</i>
                </span>
                <span id="{{ $post->id }}-count"> {{ $post->likes()->count() }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- modal for Display image -->
        <div class="modal fade" id="{{ $post->id }}">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail Image</h4>
              </div>
              <div class="modal-body">
                <div class="panel-footer">
                  <span class="who-uploaded">{{ $post->user->name }}</span>
                  <span class="when-uploaded pull-right">{{ $post->created_at->diffForHumans() }}</span>
                </div>
                <br>
                <div class="image_detail">
                  <a href="">
                    <img src="{{ asset('images/'.$post->image) }}">
                  </a>
                </div>
                <div class="description-post">
                  <i><strong>{{ $post->description }}</strong></i>
                </div>
                <div class="panel panel-default">
                  <div class="panel-body">
                    <form action="{{ route('addComment', $post->id) }}" method="post">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <textarea name="content" rows="3" class="form-control" placeholder="Your comment"></textarea>
                      </div>
                      <button type="submit" name="comment" class="btn btn-primary btn-block">Submit</button>
                    </form>
                  </div>
                </div>
                @if( $post->comments->isEmpty() )
                  <p class="text-center">Tidak ada komentar !</p>
                @else
                  @foreach( $post->comments as $comment )
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <span class="user-info">by {{ $comment->user->name }}</span>
                        <span class="user-time pull-right">{{ $comment->created_at->diffForHumans() }}</span>
                      </div>
                      <div class="panel-body">
                          <p>{{ $comment->content }}</p>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Image</h4>
      </div>
      <div class="modal-body">
        <form class="form" action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <input type="file" name="image" class="form-control">
          </div>
          <div class="form-group">
            <textarea name="description" rows="3" class="form-control" placeholder="Description"></textarea>
          </div>
          <button type="submit" name="save" class="btn btn-primary btn-block">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

<style>
  .show_image img{
    width: 100%;
    height: 230px;
  }

  .image_detail img{
    width: 100%;
    height: 500px;
  }

  .post-footer{
    padding: 15px;
    padding-top: 0;
  }

  .description-post{
      margin: 10px 0 10px 0;
  }

  @media (max-width: 1000px) {
    .show_image img{
      height: auto;
    }
    .image_detail img{
      height: auto;
    }
  }
</style>

@section('js')
<script type="text/javascript">
  function postLiked(postId, elem){
    var csrfToken = '{{ csrf_token() }}';
    var likeCount = parseInt($('#'+postId+"-count").text());

    $.post('{{ route('postlike') }}', { postId:postId, _token:csrfToken }, function(data){
      console.log(data);

      if ( data.message === 'Liked' ) {
        $('#'+postId+"-count").text(likeCount + 1);
      }else{
        $('#'+postId+"-count").text(likeCount - 1);
      }

    });
  }
</script>
@endsection
