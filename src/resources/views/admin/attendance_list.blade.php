@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance_list.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>{{$today}}の勤怠</h2>
    </div>
    <div>
        <a href="/">←前日</a>
        <span>{{$date}}</span>
        <a href="/">翌日→</a>
    </div>
    <div>
        <table>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($works as $work)
                <td>{{$work->user->name}}</td>
                <td>{{$work->start_time}}</td>
                <td>{{$work->end_time}}</td>
                <td></td>
                <td></td>
                <td><a href="/attendance/{{$work->id}}">詳細</a></td>
            @endforeach
        </table>
    </div>
</div>

@endsection