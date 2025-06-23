@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth/email_verification.css')}}">
@endsection

@section('content')
<div class="email-verification">
    @if(session('message'))
        <div class="alert-success">
            <p class="alert-success__message">{{ session('message') }}</p>
        </div>
    @endif
    <p class="email-verification__text">登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。</p>
    <div class="email-verification__link">
        <a class="email-verification__link-home" href="http://localhost:8025" target="_blank">認証はこちらから</a>
    </div>
    <form action="/email/verification-notification" method="post">
    @csrf
        <button class="email-verification__button">認証メールを再送する</button>
    </form>
</div>
@endsection