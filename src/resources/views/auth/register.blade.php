@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth/register.css')}}">
@endsection

@section('content')
<div class="register-form">
    <div class="register-form__wrapper">
        <h2 class="register-form__ttl">会員登録</h2>
    </div>
    <div class="register-form__content">
        <form action="/register" method="post" novalidate>
            @csrf
            <div class="register-form__group">
                <label class="register-form__label" for="">名前</label>
                <div class="register-form__item">
                    <input class="register-form__input" type="text" name="name" value="{{old('name')}}">
                </div>
                <div class="register-form__error">
                    @error('name')
                    <p class="register-form__error-message">{{$message}}</p>
                    @enderror
                </div>

            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="">メールアドレス</label>
                <div class="register-form__item">
                    <input class="register-form__input"  type="email" name="email" value="{{old('email')}}">
                </div>
                <div class="register-form__error">
                    @error('email')
                    <p class="register-form__error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="register-form__group">
                <label for="" class="register-form__label">パスワード</label>
                <div class="register-form__item">
                    <input class="register-form__input"  type="password" name="password">
                </div>
                <div class="register-form__error">
                    @error('password')
                    <p class="register-form__error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="register-form__group">
                <label class="register-form__label">パスワード確認</label>
                <div class="register-form__item">
                    <input class="register-form__input"  type="password" name="password_confirmation">
                </div>
                <div class="register-form__error">
                    @error('password_confirmation')
                    <p class="register-form__error-message">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="register-form__button">
                <button class="register-form__submit">登録する</button>
            </div>
            <div class="register-form__link">
                <a class="register-form__link-login" href="/login">ログインはこちら</a>
            </div>
        </form>
        
    </div>
</div>
@endsection