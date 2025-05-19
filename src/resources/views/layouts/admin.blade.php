@extends('layouts.default')

@section('header')
    <nav>
        <div>
            <div>
                <a href="/admin/attendance/list">勤怠一覧</a>
            </div>
            <div>
                <a href="/admin/staff/list">スタッフ一覧</a>
            </div>
            <div>
                <a href="/stamp_correction_request/list">申請一覧</a>
            </div>
            <div>
                <form action="/logout" method="post">
                    @csrf
                    <button>ログアウト</button>
                </form>
            </div>
            
        </div>
    </nav>
@endsection