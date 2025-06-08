@extends('layouts.default')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="login-form">
    <div class="login-form__wrapper">
        <h2 class="login-form__ttl">管理者ログイン</h2>
    </div>
    <div class="login-form__content">
        <form action="/admin/login" method="post" novalidate>
            <!-- <input type="hidden" name="guard" value="admin"> -->
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="">メールアドレス</label>
                <div class="login-form__item">
                    <input class="login-form__input" type="email" name="email" value="{{old('email')}}">
                </div>
                <div class="login-form__error">
                    @error('email')
                    <p class="login-form__error-message">
                        {{$message}}
                    </p>
                    @enderror
                </div>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="">パスワード</label>
                <div class="login-form__item">
                    <input class="login-form__input" type="password" name="password">
                </div>
                @error('password')
                    <p class="login-form__error-message">
                        {{$message}}
                    </p>
                    @enderror
                </div>
            </div>
            <div class="login-form__button">
                <button class="login-form__submit">管理者ログインする</button>
            </div>
        </form>
    </div>
</div>
@endsection