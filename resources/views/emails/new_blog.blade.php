@extends('emails.template', [
    'namePage' => 'New Blog'
])

@section('content')

<style>
	.text-center{
		text-align: center;
	}
</style>

  <table class="table" bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
  <tr>
    <td class="h1" style="padding: 5% 0 0 5%;">{{ $blog_title }}</td>
  </tr>
  <tr>
    <td class="bodycopy borderbottom" style="padding: 5%;">
        @php
            $src = ($image) ? '/storage/journals/'.$image : '/storage/no-photo-available.png';
        @endphp
        <div style="margin: 0 auto 0 auto; width: 40%">
            <img src="{{ asset($src) }}" class="img-responsive" width="100%">
        </div>
        <br><br/>
        {{ $caption }}
        <br><br/><br><br/>
        <div class="button">
            <a href="{{ route('blogs', ['slug' => $slug]) }}" style="background: #e05443; padding: 2% 5%;">Read More</a>
        </div>
    </td>
  </tr>
     <tr>
    <td class="innerpadding bodycopy">
      If you did not initiate this request, please contact us immediately at <a href="mailto:support@fumaco.com">support@fumaco.com</a>
    </td>
  </tr>
</table>
@endsection