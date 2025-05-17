@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <div>
        <h2>勤怠一覧</h2>
    </div>
    <div>
        <a href="/attendance_list/last_month/?date={{$date}}">←前月</a>
        <span>{{$month}}</span>
        <a href="/attendance_list/next_month/?date={{$date}}">翌月→</a>
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
                <td>{{$work->date}}</td>
                <td>{{substr($work->start_time,0,5)}}</td>
                <td>{{substr($work->end_time,0,5)}}</td>
                <td>
                @foreach($work->rests as $rest)
                {{$rest->total_rest()}} 
                @endforeach
                </td>
                <td>{{$work->total_work()}}</td>
                <td><a href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection