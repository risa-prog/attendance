@extends('layouts.default')

@section('header')
    <nav>
        <div>
            <a href="/attendance">勤怠</a>
            <a href="/attendance/list">勤怠一覧</a>
            <a href="/stamp_correction_request/list">申請</a>
            <form action="/logout" method="post">
            @csrf
            <button>ログアウト</button>
            </form>
        </div>
    </nav>
@endsection