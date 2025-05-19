@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <div>
        <h2>勤怠一覧</h2>
    </div>
    <div>
        <a href="/attendance_list/previous_month/?date={{$date}}">←前月</a>
        <span>{{$date->format('Y/m')}}</span>
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
                <td>{{ \Carbon\Carbon::parse($work->date)->translatedFormat('m/d(D)')}}</td>
                <td>{{substr($work->start_time,0,5)}}</td>
                <td>{{substr($work->end_time,0,5)}}</td>
                <td>
                @foreach($work->rests as $rest)
                {{$rest->total_rest()}} 
                @endforeach
                </td>
                <td></td>
                <td><a href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection