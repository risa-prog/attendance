@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance_list.css')}}">
@endsection

@section('content')
<div class="attendance-list">
    <h2 class="attendance-list__ttl">{{$day->format('Y年n月j日')}}の勤怠</h2>
    <div class="attendance-list__date">
        <div class="attendance-list__previous-day">
            <a class="attendance-list__link" href="/admin/attendance/list?tab=previous&date={{$day}}">←前日</a>
        </div>
        <div class="attendance-list__target-day">
            <div class="attendance-list__date-img">
                <img class="img-calendar" src="{{asset('img/スケジュールカレンダーのアイコン素材.png')}}" alt="">
            </div>
            <span class="attendance-list__span">{{$day->format('Y/m/d')}}</span>
        </div>
        <div class="attendance-list__next-day">
            <a class="attendance-list__link" href="/admin/attendance/list?tab=next&date={{$day}}">翌日→</a>
        </div>
    </div>
    <div class="attendance-list__content">
        <table class="attendance-list__table">
            <tr class="attendance-list__table-row">
                <th class="attendance-list__table-heading">名前</th>
                <th class="attendance-list__table-heading">出勤</th>
                <th class="attendance-list__table-heading">退勤</th>
                <th class="attendance-list__table-heading">休憩</th>
                <th class="attendance-list__table-heading">合計</th>
                <th class="attendance-list__table-heading">詳細</th>
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
            <tr>
                <td class="attendance-list__table-data">{{$work->user->name}}</td>
                <td class="attendance-list__table-data">{{substr($work->work_start,0,5)}}</td>
                <td class="attendance-list__table-data">
                    {{substr($work->work_end,0,5)}}
                </td>
                <td class="attendance-list__table-data">{{sprintf('%02d:%02d',$restHours,$restMinutes)}}</td>
                @if($work->work_end !== null)
                <td class="attendance-list__table-data">{{sprintf('%02d:%02d',$workHours,$workMinutes)}}</td>
                @else
                    <td class="attendance-list__table-data"></td>
                @endif
                    <td class="attendance-list__table-data">
                        <a class="attendance-list__table-link" href="/attendance/{{$work->id}}">詳細</a>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
</div>

@endsection