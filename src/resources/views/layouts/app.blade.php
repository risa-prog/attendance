@extends('layouts.default')

@section('header')
<nav class="header__nav">
    @if (Auth::guard('admin')->check())
    <ul class="header__nav-list">
        <li class="header__nav-item"><a class="header__nav-list-link" href="/admin/attendance/list">勤怠一覧</a></li>
        <li class="header__nav-item"><a class="header__nav-list-link" href="/admin/staff/list">スタッフ一覧</a></li>
        <li class="header__nav-item"><a class="header__nav-list-link" href="/stamp_correction_request/list">申請一覧</a></li>
        <li class="header__nav-item">
            <form action="/admin/logout" method="post">
                @csrf
                <button class="header__nav-button">ログアウト</button>
            </form>
        </li>
    </ul>
    @elseif (Auth::guard('web')->check())
    <ul class="header__nav-list">
        <li class="header__nav-item"><a class="header__nav-list-link" href="/attendance">勤怠</a></li>
        <li class="header__nav-item"><a class="header__nav-list-link" href="/attendance/list">勤怠一覧</a></li>
        <li class="header__nav-item"><a class="header__nav-list-link" href="/stamp_correction_request/list">申請</a></li>
        <li class="header__nav-item">
            <form action="/logout" method="post">
                @csrf
                <button class="header__nav-button">ログアウト</button>
            </form>
        </li>
    </ul>
    @endif
</nav>
@endsection