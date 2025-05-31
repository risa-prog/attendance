@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff.css')}}">
@endsection

@section('content')
<div class="staff">
    <h2 class="staff__ttl">{{$user->name}}さんの勤怠</h2>
    <div class="staff__date">
        <a class="staff__previous-month" href="/admin/attendance/staff/{{$user->id}}?tab=previous&date={{$date}}">←前月</a>
        <span class="staff__span">{{$date->format('Y/m')}}</span>
        <a class="staff__next-month" href="/admin/attendance/staff/{{$user->id}}?tab=next&date={{$date}}">翌月→</a>
    </div>
    <div class="staff__form">
        <form action="/admin/attendance/staff/csv-download" method="post">
            @csrf
            <div class="staff__form-content">
                <table class="staff__form-table">
                    <tr class="staff__form-table-row">
                        <th class="staff__form-table-heading">日付</th>
                        <th class="staff__form-table-heading">出勤</th>
                        <th class="staff__form-table-heading">退勤</th>
                        <th class="staff__form-table-heading">休憩</th>
                        <th class="staff__form-table-heading">合計</th>
                        <th class="staff__form-table-heading">詳細</th>
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
                        <tr class="staff__form-table-row">
                            <td class="staff__form-table-data">{{Carbon\Carbon::parse($work->date)->translatedFormat('m/d(D)')}}</td>
                            <input type="hidden" name="date[]" value="{{$work->date}}">
                            <td class="staff__form-table-data">{{substr($work->work_start,0,5)}}</td>
                            <input type="hidden" name="work_start[]" value="{{substr($work->work_start,0,5)}}">
                            <td class="staff__form-table-data">{{substr($work->work_end,0,5)}}</td>
                            <input type="hidden" name="work_end[]" value="{{substr($work->work_end,0,5)}}">
                            <td class="staff__form-table-data">{{sprintf('%02d:%02d',$restHours,$restMinutes)}}</td>
                            <input type="hidden" name="totalRestTime[]" value="{{sprintf('%02d:%02d',$restHours,$restMinutes)}}">
                            <td class="staff__form-table-data">{{sprintf('%02d:%02d',$workHours,$workMinutes)}}</td>
                            <input type="hidden" name="totalWorkTime[]" value="{{sprintf('%02d:%02d',$workHours,$workMinutes)}}">
                            <td class="staff__form-table-data"><a class="staff__form-table-link" href="/attendance/{{$work->id}}">詳細</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>    
            <div class="staff__form-button">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <button class="staff__form-submit">CSV出力</button>
            </div>
        </form>
    </div>
</div>
@endsection