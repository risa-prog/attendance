@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>{{$user->name}}さんの勤怠</h2>
    </div>
    <div>
        <a href="">←前月</a>
        <span>{{$date}}</span>
        <a href="">翌月→</a>
    </div>
    <div>
        <table>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($user->works as $work)
            <tr>
                <td>{{$work->date}}</td>
                <td>{{$work->start_time}}</td>
                <td>{{$work->end_time}}</td>
                <td></td>
                <td></td>
                <td><a href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection