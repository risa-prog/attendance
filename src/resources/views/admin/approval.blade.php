@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/approval.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>勤怠詳細</h2>
    </div>
    <div>
        <form action="/stamp_correction_request/approve" method="post">
            @csrf
            <table>
                <tr>
                    <th>名前</th>
                    <td>{{$correction->user->name}}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{$correction->work->date}}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>{{$correction->punchIn}}~{{$correction->punchOut}}</td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>{{$correction->break_begins}}~{{$correction->break_ends}}</td>
                </tr>
                
                <tr>
                    <th>休憩2</th>
                    <td></td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>{{$correction->note}}</td>
                </tr>
            </table>
            <div>
                <button>承認</button>
            </div>
        </form>
    </div>
</div>
@endsection