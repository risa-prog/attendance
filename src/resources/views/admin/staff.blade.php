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
        <a href="/attendance/staff/previous_month/?date={{$date}}&user_id={{$user->id}}">←前月</a>
        <span>{{$date->format('Y/m')}}</span>
        <a href="/attendance/staff/next_month/?date={{$date}}&user_id={{$user->id}}">翌月→</a>
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
            @foreach($works as $work)
            <tr>
                <td>{{Carbon\Carbon::parse($work->date)->translatedFormat('m/d(D)')}}</td>
                <td>{{substr($work->start_time,0,5)}}</td>
                <td>{{substr($work->end_time,0,5)}}</td>
                <td></td>
                <td></td>
                <td><a href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection