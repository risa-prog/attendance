@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff.css')}}">
@endsection

@section('content')
<div class="staff">
    <h2 class="staff__ttl">{{$user->name}}さんの勤怠</h2>
    <div class="staff__date">
        <a class="staff__previous-month" href="/attendance/staff/previous_month/?date={{$date}}&user_id={{$user->id}}">←前月</a>
        <span class="staff__span">{{$date->format('Y/m')}}</span>
        <a class="staff__next-month" href="/attendance/staff/next_month/?date={{$date}}&user_id={{$user->id}}">翌月→</a>
    </div>
    <div class="staff__content">
        <table class="staff__table">
            <tr class="staff__table-row">
                <th class="staff__table-heading">日付</th>
                <th class="staff__table-heading">出勤</th>
                <th class="staff__table-heading">退勤</th>
                <th class="staff__table-heading">休憩</th>
                <th class="staff__table-heading">合計</th>
                <th class="staff__table-heading">詳細</th>
            </tr>
            @foreach($works as $work)
            <tr class="staff__table-row">
                <td class="staff__table-data">{{Carbon\Carbon::parse($work->date)->translatedFormat('m/d(D)')}}</td>
                <td class="staff__table-data">{{substr($work->start_time,0,5)}}</td>
                <td class="staff__table-data">{{substr($work->end_time,0,5)}}</td>
                <td class="staff__table-data"></td>
                <td class="staff__table-data"></td>
                <td class="staff__table-data"><a class="staff__table-link" href="/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection