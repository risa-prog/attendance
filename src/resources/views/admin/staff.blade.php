@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff.css')}}">
@endsection

@section('content')
<div class="staff">
    <h2 class="staff__ttl">{{$user->name}}さんの勤怠</h2>
    <div class="staff__date">
        <div class="staff__previous-month">
            <a class="staff__month-link" href="/admin/attendance/staff/{{$user->id}}?tab=previous&date={{$date}}">←前月</a>
        </div>
        <div class="staff__target-month">
            <div>
                <img class="img-calendar" src="{{asset('img/スケジュールカレンダーのアイコン素材.png')}}" alt="">
            </div>
            <span class="staff__month-span">
                {{$date->format('Y/m')}}
            </span>
        </div>
        <div class="staff__next-month">
            <a class="staff__month-link" href="/admin/attendance/staff/{{$user->id}}?tab=next&date={{$date}}">翌月→</a>
        </div>
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
                    @foreach($dates as $date)
                        @php
                            $key = $date->format('Y-m-d');

                            $work = $works->get($key);

                            if($work !== null){
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
                            }
                        @endphp
                        <tr class="staff__form-table-row">
                            <td class="staff__form-table-data">
                                {{ $date->translatedFormat('m/d(D)') }}
                            </td>
                            @if($work !== null)
                                <input type="hidden" name="date[]" value="{{$date}}">
                            @endif
                                <td class="staff__form-table-data">
                                    {{ substr(optional($work)->work_start,0,5) }}
                                </td>
                            @if($work !== null)
                            <input type="hidden" name="work_start[]" value="{{ substr(optional($work)->work_start,0,5) }}">
                            @endif
                                <td class="staff__form-table-data">
                                    {{ substr(optional($work)->work_end,0,5) }}
                                </td>
                            @if($work !== null)
                                <input type="hidden" name="work_end[]" value="{{ substr(optional($work)->work_end,0,5) }}">
                            @endif
                            @if($work !== null)
                                <td class="staff__form-table-data">
                                    {{ sprintf('%02d:%02d',$restHours,$restMinutes) }}
                                </td>
                                <input type="hidden" name="totalRestTime[]" value="{{sprintf('%02d:%02d',$restHours,$restMinutes)}}">
                                <td class="staff__form-table-data">
                                    {{sprintf('%02d:%02d',$workHours,$workMinutes)}}
                                </td>
                                <input type="hidden" name="totalWorkTime[]" value="{{sprintf('%02d:%02d',$workHours,$workMinutes)}}">
                            @else
                                <td class="staff__form-table-data">
                                <td class="staff__form-table-data">
                            @endif
                            <td class="staff__form-table-data">
                                @if($work !== null)
                                    <a class="staff__form-table-link" href="/attendance/{{optional($work)->id}}">詳細</a>
                                @else
                                    <a class="staff__form-table-link" href="">詳細</a>
                                @endif
                            </td>
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