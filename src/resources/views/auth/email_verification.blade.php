@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{asset('css/mailhog/index.css')}}">
@endsection

@section('content')
<div>
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>
    <form action="" method="post">
        @csrf
        <button>認証はこちらから</button>
    </form>
    <form action="/email/verification-notification" method="post">
        @csrf
        <button>認証メールを再送する</button>
    </form>
</div>
@endsection