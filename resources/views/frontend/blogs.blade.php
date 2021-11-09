@extends('frontend.layout', [
'namePage' => 'Blog Content',
'activePage' => 'blog'
])

@section('content')
<main>
    <br>
    <br>
    <br>
</main>
<main style="background-color:#ffffff;" class="products-head">
    <div class="container">
        <div class="row" style="padding-left: 0px !important; padding-right: 0px !important;">
            <div class="col-lg-12">
                <br>
                <br>
                <p style="font-family: 'poppins', sans-serif !important;"  style="color:#373b3e !important; font-weight: 400 !important; font-size:12px !important;">
                    Home&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;Blogs </p>
                <hr>
            </div>

            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <br>
                <br>
            </div>
        </div>
    </div>
</main>
<main style="padding-left:7%; padding-right:7%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <center>
                    <img src="{{ asset('/assets/journal/'.$blog->blogprimaryimage) }}" alt="fumaco"
                        class="img-responsive" style="width: 100% !important;">
                </center>
                <br>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important; font-size: 10pt"  class="blog-font-a">{{ $blog->datepublish }} | {{ $comment_count->count() }} Comment(s)</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important; color: #0F6DB7 !important"  class="fumacoFont_card_title">{{ $blog->blogtitle }}
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important; font-size: 10pt"  class="blog-font-a">{{ $blog->blogtype }}</p>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important;"  class="font1color">&nbsp;</p>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important;">{{ $blog->blog_caption }}</p>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-lg-12">
                <p style="font-family: 'poppins', sans-serif !important;"  class="font1color">&nbsp;</p>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                {!! $blog->blogcontent !!}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important;">Tags : <button style=" color: #0F6DB7 !important" class="btn btn-outline-primary1 btn-sm">Products</button>&nbsp;<button style=" color: #0F6DB7 !important" class="btn btn-outline-primary1 btn-sm">Applications</button></p>

                <div id="fb-root"></div>
                <script>
                    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>

                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0&appId=974569840046115&autoLogAppEvents=1"
                    nonce="1VBl9fa6"></script>
                <!-- Your share button code -->
                <div class="fb-like" data-href="https://test.fumaco.com.ph/blog?id={{ $id }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
            </div>

            <div class="col-lg-6" style="text-align:right;">
                <p style="font-family: 'poppins', sans-serif !important;"  class="blog-font-a font1color">

                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <br>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <br>
                @if(session()->has('comment_message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="status">
                        {{ session()->get('comment_message') }}
                    </div>
                @endif

                @if(session()->has('reply_message'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="status">
                        {{ session()->get('reply_message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="row" style="background-color:#f4f4f4;">
            <div class="col-lg-12">
                <div class="row">
                    <form action="add_comment" method="post">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <br>
                                <p style="font-family: 'poppins', sans-serif !important;"  class="font2color animated animatedFadeInUp fadeInUp">LEAVE A COMMENT</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <p style="font-family: 'poppins', sans-serif !important;"  class="animated animatedFadeInUp fadeInUp" style="color:#a9a9a9 !important;">Your email address will not be published. Required fields are marked *</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <textarea class="form-control caption_1 animated animatedFadeInUp fadeInUp" rows="5" id="comment" name="comment" required></textarea>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control caption_1 animated animatedFadeInUp fadeInUp" placeholder="Name *" name="fullname" id="fullname" required>
                            </div>

                            <div class="col">
                                <input type="email" class="form-control caption_1 animated animatedFadeInUp fadeInUp" placeholder="Email *" name="fullemail" id="fullemail" required>
                                <input type="hidden" class="form-control caption_1 animated animatedFadeInUp fadeInUp" name="idcode" id="idcode" value="{{ $id }}" required>
                            </div>
                        </div>
                        <input class="btn btn-primary mt-3 caption_1 animated animatedFadeInUp fadeInUp" type="submit" value="POST COMMENT">
                        <br>&nbsp;
                    </form>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                &nbsp;
                <br>
            </div>
        </div>

        <div class="row" style="background-color:#ffffff;">

            <br>

            <div class="col-lg-12">
              <span style="font-family: 'poppins', sans-serif !important;"  style="font-size:24px; font-weight:300;">&nbsp;
              &nbsp;Comments<span style="font-family: 'poppins', sans-serif !important;" >&nbsp;&nbsp;<span style="font-family: 'poppins', sans-serif !important;"  style="font-size:10px; font-weight:300;"> (Avatar powered by gravatar.com)</span>
            </div>

            <div class="col-lg-12">
              <hr>
            </div>
            <br>
            <br>

            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
              <!-- comments -->
                @foreach($comments_arr as $comment)
                <div class="row">
                    @php
                        $useravatar = md5( strtolower( trim( $comment['email'] ) ) );
                    @endphp
                    <div class="col-lg-2">
                        <img src="https://www.gravatar.com/avatar/{{ $useravatar }}&d=https://secure.gravatar.com/avatar/56445b52ab352ef83cfff87e35d9929a?s=150&d=mm&r=g" />
                    </div>
                    <div class="col-lg-10">
                        <span style="font-family: 'poppins', sans-serif !important;"  class="font3color blog-font-b">{{ $comment['name'] }}</span> on {{ $date }}
                        <br>
                        <span style="font-family: 'poppins', sans-serif !important;"  class="fumacoFont_card_caption">{{ $comment['comment'] }}</span>
                        <br>
                        <br>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="x1{{ $comment['id'] }}">Reply</button>
                        <br>
                        <script>
                            $(document).ready(function(){
                                $("#x1{{ $comment['id'] }}").click(function(){
                                    $("#x2{{ $comment['id'] }}").show();
                                });
                            });
                        </script>
                        <!--reply to replycomment -->
                        <div class="row" id="x2{{ $comment['id'] }}" style="display:none;">
                            <form action="add_reply" method="post" name="form2">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control caption_1" rows="5" id="reply_comment" name="reply_comment" required></textarea>
                                    </div>
                                </div>

                                <br>

                                <div class="row" >
                                    <div class="col">
                                        <input type="text" class="form-control caption_1" placeholder="Name *" name="reply_name" required>
                                    </div>

                                    <div class="col">
                                        <input type="email" class="form-control caption_1" placeholder="Email *" name="reply_email" required>
                                        <input type="hidden" class="form-control caption_1"  name="reply_blogId" value="{{ $id }}">
                                        <input type="hidden" class="form-control caption_1" name="reply_replyId" value="{{ $comment['id'] }}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 caption_1">&nbsp;&nbsp;&nbsp;REPLY  COMMENT&nbsp;&nbsp;&nbsp;</button>
                                <br>
                                <br>
                            </form>
                            <br>
                            <br>
                            <br>
                        </div>
                        <br>
                        <!-- reply comment -->
                        @foreach($comment['reply_comment'] as $reply)
                            <div class="row">
                                <div class="col-lg-2">
                                    <img src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec077210c08d50?s=100&d=https://secure.gravatar.com/avatar/56445b52ab352ef83cfff87e35d9929a?s=80&d=mm&r=g" />
                                </div>

                                <div class="col-lg-10">
                                    <span style="font-family: 'poppins', sans-serif !important;"  class="font3color blog-font-b">{{ $reply->blog_name }}</span> on {{ $reply->blog_date }}
                                    <br>
                                    <span style="font-family: 'poppins', sans-serif !important;"  class="fumacoFont_card_caption">{{ $reply->blog_comments }}</span>
                                    <br><br>
                                </div>
                                <br>
                            </div>
                        @endforeach
                        <br>
                        <hr style="color:#b3aaaa !important;">
                    </div>
                </div>
                <!-- end comments -->
                <br>
                <hr>
                <br>
                <!-- row of comments -->
                @endforeach
            </div>
        </div>
    </div>
    <br>&nbsp;
    <br>&nbsp;
</main>

@endsection

@section('style')
<style>
    .MsoNormal span{
        font-family: 'poppins', sans-serif !important;
    }

    .MsoNormal{
        font-family: 'poppins', sans-serif !important;
    }
</style>
@endsection