@extends('frontend.layout', [
'namePage' => $blog->blogtitle,
'activePage' => 'blog'
])

@section('meta')
    @php
        $b_tags = implode(",", $tags);
        $image = ($blog->blogprimaryimage) ? '/storage/journals/'.$blog->blogprimaryimage : '/storage/no-photo-available.png';

        if (Str::startsWith($current = url()->current(), 'https://www')) {
            $s_url = $current;
        } else {
            $s_url = str_replace('https://', 'https://www.', $current);
        }
    @endphp
    <meta name="description" content="{{ $blog->blog_caption }}">
	<meta name="keywords" content="{{ $b_tags }}" />
    <meta property="og:url" content="{{ $s_url }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $blog->blogtitle }}" />
    <meta property="og:description" content="{{ $blog->blog_caption }}" />
    <meta property="og:image" content="{{ asset($image) }}" />
@endsection

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
    <div class="container">
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <center>
                    
                  @php
                  $image = ($blog->blogprimaryimage) ? '/storage/journals/'.$blog->blogprimaryimage : '/storage/no-photo-available.png';
                  $image_webp = ($blog->blogprimaryimage) ? '/storage/journals/'.explode(".", $blog->blogprimaryimage)[0] .'.webp' : '/storage/no-photo-available.png';
                @endphp
            
                <picture>
                  <source srcset="{{ asset($image_webp) }}" type="image/webp" class="img-responsive card-img-top" style="width: 100% !important;">
                  <source srcset="{{ asset($image) }}" type="image/jpeg" class="img-responsive card-img-top" style="width: 100% !important;">
                  <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $blog->blogprimaryimage)[0], '-') }}" class="img-responsive card-img-top" style="width: 100% !important;">
                 </picture>
                 

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

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important;">{{ $blog->blog_caption }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                {!! $blog->blogcontent !!}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 animated animatedFadeInUp fadeInUp">
                <p style="font-family: 'poppins', sans-serif !important;">
                    Tags :
                    @if ($blog_tags)
                        @foreach ($tags as $tag)
                            <button style=" color: #0F6DB7 !important; font-weight: 300 !important" class="btn btn-outline-primary1 btn-sm">{{ $tag }}</button>
                        @endforeach
                    @endif
                </p>

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
                <div class="fb-like" data-href="{{ $s_url }}" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
            </div>

            <div class="col-lg-6" style="text-align:right;" id="comments">
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
                    <form action="/add_comment" method="post">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="recaptcha_v3">
                        <div class="row">
                            <div class="col">
                                <br>
                                <p style="font-family: 'poppins', sans-serif !important;"  class="font2color animated animatedFadeInUp fadeInUp">LEAVE A COMMENT</p>
                            </div>
                        </div>
                        @if (Auth::check())
                            <div class="row">
                                <div class="col mx-auto">
                                    <p style="font-family: 'poppins', sans-serif !important;"  class="font2color animated animatedFadeInUp fadeInUp">Logged in as {{ Auth::user()->f_name." ".Auth::user()->f_lname }}</p>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col">
                                    <p style="font-family: 'poppins', sans-serif !important;"  class="animated animatedFadeInUp fadeInUp" style="color:#a9a9a9 !important;">Your email address will not be published. Required fields are marked *</p>
                                </div>
                            </div>
                        @endif

                        @if(count($errors->all()) > 0)
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            @foreach ($errors->all() as $error)
                            <span class="d-block">{{ $error }}</span>
                            @endforeach
                        </div>
                        @endif

                        <div class="row">
                            <div class="col">
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <textarea class="form-control caption_1 animated animatedFadeInUp fadeInUp" rows="5" id="comment" name="comment" placeholder="Message *" required></textarea>
                            </div>
                        </div>

                        <br>
                        @if (!Auth::check())
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control caption_1 animated animatedFadeInUp fadeInUp" placeholder="Name *" name="fullname" id="fullname" required>
                                </div>

                                <div class="col">
                                    <input type="email" class="form-control caption_1 animated animatedFadeInUp fadeInUp" placeholder="Email *" name="fullemail" id="fullemail" required>
                                </div>
                            </div>
                        @endif
                        <input type="text" class="form-control caption_1 animated animatedFadeInUp fadeInUp" name="idcode" id="idcode" value="{{ $id }}" required hidden>
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
              &nbsp;Comments<span style="font-family: 'poppins', sans-serif !important;" >
            </div>

            <div class="col-lg-12">
              <hr>
            </div>
            <br>
            <br>

            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
              <!-- comments -->
                @forelse($comments_arr as $key => $comment)
                <div class="row">
                    @php
                        $useravatar = md5( strtolower( trim( $comment['email'] ) ) );
                    @endphp
                    <div class="col-md-1">
                        <img src="https://www.gravatar.com/avatar/{{ $useravatar }}&d=https://secure.gravatar.com/avatar/56445b52ab352ef83cfff87e35d9929a?s=150&d=mm&r=g" width="100%"/>
                    </div>
                    <div class="col-md-10">
                        <p style="font-family: 'poppins', sans-serif !important; line-height: 5px !important"><b>{{ $comment['name'] }}</b></p>
                        <p style="font-size: 9pt !important; line-height: 2px !important">{{ $comment['date'] }}</p>
                        <span style="font-family: 'poppins', sans-serif !important;"  class="fumacoFont_card_caption">{{ $comment['comment'] }}</span>
                        <br>
                        <br>
                        <button type="button" class="btn btn-sm btn-outline-secondary reply {{ $key }}" id="x1{{ $comment['id'] }}">Reply</button>
                        <br>
                        <div class="row" id="reply-field-{{ $key }}" style="display:none;">
                            <form action="/add_comment" method="post" name="form2">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <br>
                                    </div>
                                </div>

                                @if (Auth::check())
                                    <div class="row">
                                        <div class="col mx-auto">
                                            <p style="font-family: 'poppins', sans-serif !important;"  class="font2color">Logged in as {{ Auth::user()->f_name." ".Auth::user()->f_lname }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control caption_1" rows="5" id="reply_comment" name="comment" required></textarea>
                                    </div>
                                </div>

                                <br>
                                @if (!Auth::check())
                                    <div class="row" >
                                        <div class="col">
                                            <input type="text" class="form-control caption_1" placeholder="Name *" name="fullname" required>
                                        </div>

                                        <div class="col">
                                            <input type="email" class="form-control caption_1" placeholder="Email *" name="fullemail" required>
                                        </div>
                                    </div>
                                @endif
                                {{-- <input type="hidden" class="form-control caption_1"  name="reply_blogId" value="{{ $id }}"> --}}
                                <input type="hidden" class="form-control caption_1" name="reply_replyId" value="{{ $comment['id'] }}">
                                <input type="text" class="form-control" name="idcode" id="idcode" value="{{ $id }}" required hidden>
                                <button type="submit" class="btn btn-primary mt-3 caption_1">&nbsp;&nbsp;&nbsp;REPLY  COMMENT&nbsp;&nbsp;&nbsp;</button>
                                <br>
                                <br>
                            </form>
                            <br>
                            <br>
                            <br>
                        </div>
                        <br>&nbsp;
                        <!-- reply comment -->
                        @foreach($comment['reply_comment'] as $reply)
                            <div class="row">
                                <div class="col-lg-1">
                                    <img src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec077210c08d50?s=100&d=https://secure.gravatar.com/avatar/56445b52ab352ef83cfff87e35d9929a?s=80&d=mm&r=g" width="100%"/>
                                </div>

                                <div class="col-lg-10 mb-3">
                                    <p style="font-family: 'poppins', sans-serif !important; line-height: 5px !important"><b>{{ $reply['blog_name'] }}</b></p>
                                    <p style="font-size: 8pt !important; line-height: 2px !important">{{ $reply['blog_date'] }}</p>
                                    <span style="font-family: 'poppins', sans-serif !important;"  class="fumacoFont_card_caption">{{ $reply['blog_comments'] }}</span>
                                    <br><br>
                                </div>
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
                @empty
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <p>No Comments.</p>
                        </div>
                    </div>
                @endforelse
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

@section('script')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.api_site_key') }}"></script>
<script> 
  grecaptcha.ready(function() {
    grecaptcha.execute("{{ config('recaptcha.api_site_key') }}", {action: 'homepage'}).then(function(token) {
      if(token) {
        $("#recaptcha_v3").val(token); 
      } 
    });
  });
</script> 
    <script>
        $(document).ready(function(){
            $('.reply').click(function(){
                var key = $(this).attr('class').split(' ').pop();
                $('#reply-field-'+key).show();
            });

        });
    </script>
@endsection