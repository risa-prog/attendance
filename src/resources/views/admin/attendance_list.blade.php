@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance_list.css')}}">
@endsection

@section('content')
<div class="attendance-list">
     <h2 class="attendance-list__ttl">{{$today->format('Y年n月j日')}}の勤怠</h2>
    <div class="attendance-list__date">
        <a class="attendance-list__previous-day" href="/admin/attendance/list/previous_day/?date={{$today}}">←前日</a>
        <span class="attendance-list__span">{{$today->format('Y/m/d')}}</span>
        <a class="attendance-list__next-day" href="/admin/attendance/list/next_day/?date={{$today}}">翌日→</a>
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
                <td class="attendance-list__table-data">{{$work->user->name}}</td>
                <td class="attendance-list__table-data">{{substr($work->start_time,0,5)}}</td>
                <td class="attendance-list__table-data">{{substr($work->end_time,0,5)}}</td>
                <td class="attendance-list__table-data"></td>
                <td class="attendance-list__table-data"></td>
                <td class="attendance-list__table-data"><a class="attendance-list__table-link" href="/attendance/{{$work->id}}">詳細</a></td>
            @endforeach
        </table>
    </div>
</div>

@endsection