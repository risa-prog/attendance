@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance/list.css')}}">
@endsection

@section('content')
<div class="attendance-list">
    <div>
        <h2 class="attendance-list__ttl">勤怠一覧</h2>
    </div>
    <div class="attendance-list__date">
        <div class="attendance-list__previous-month">
            <a class="previous-month-link" href="/attendance/list/?tab=previous&date={{$date}}">←前月</a>
        </div>
        <div class="attendance-list__target-month">
            <div class="attendance-list__date-img">
                <img class="img-calendar" src="{{asset('img/スケジュールカレンダーのアイコン素材.png')}}" alt="">
            </div>
            <span class="target-month-span">{{$date->format('Y/m')}}</span>
        </div>
        <div class="attendance-list__next-month">
            <a class="next-month-link" href="/attendance/list/?tab=next&date={{$date}}">翌月→</a>
        </div>
    </div>
    <div class="attendance-list__content">
        <table class="attendance-table">
            <tr class="attendance-table__row">
                <th class="attendance-table__heading">日付</th>
                <th class="attendance-table__heading">出勤</th>
                <th class="attendance-table__heading">退勤</th>
                <th class="attendance-table__heading">休憩</th>
                <th class="attendance-table__heading">合計</th>
                <th class="attendance-table__heading">詳細</th>
            </tr>
            @foreach($works as $work)
            @php
            $start = \Carbon\Carbon::parse($work->work_start);
            $end = \Carbon\Carbon::parse($work->work_end);
            $workingMinutes = $end->diffInMinutes($start);

            $totalRestMinutes = $work->rests->sum(function ($rest) {
            return \Carbon\Carbon::parse($rest->rest_start)->diffInMinutes($rest->rest_end);
            });
            $restHours = floor($totalRestMinutes / 60);
            $restMinutes = $totalRestMinutes % 60;

            $actualWorkingMinutes = $workingMinutes - $totalRestMinutes;

            $workHours = floor($actualWorkingMinutes / 60);
            $workMinutes = $actualWorkingMinutes % 60;
            @endphp
            <tr class="attendance-table__row">
                <td class="attendance-table__data">{{ \Carbon\Carbon::parse($work->date)->translatedFormat('m/d(D)')}}</td>
                <td class="attendance-table__data">{{substr($work->work_start,0,5)}}</td>
                <td class="attendance-table__data">{{substr($work->work_end,0,5)}}</td>
                <td class="attendance-table__data">{{sprintf('%02d:%02d',$restHours,$restMinutes)}}</td>
                <td class="attendance-table__data">{{sprintf('%02d:%02d',$workHours,$workMinutes)}}</td>
                <td class="attendance-table__data"><a class="attendance-table__link" href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection