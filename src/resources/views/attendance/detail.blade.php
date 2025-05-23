@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance/detail.css')}}">
@endsection

@section('content')
<div class="attendance-detail">
    <h2 class="attendance-detail__ttl">勤怠詳細</h2>
    <div class="attendance-detail__content">
        <form class="attendance-detail__form" action="/attendance" method="post">
        @csrf
            <input type="hidden" name="work_id" value="{{$work->id}}">
            <input type="hidden" name="status" value="1">
            <table class="attendance-detail__table">
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">名前</th>
                    <td class="attendance-detail__table-data">{{$work->user->name}}</td>
                </tr>
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">日付</th>
                    <td class="attendance-detail__table-data">
                        <span class="attendance-detail__year">{{\Carbon\Carbon::parse($work->date)->format('Y年')}}</span>
                        <span class="attendance-detail__day">
                            {{\Carbon\Carbon::parse($work->date)->format('n月j日')}}</td>
                        </span>
                </tr>
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">出勤・退勤</th>
                    @if($work->workCorrection === null || $work->workCorrection->status === 2)
                    <td class="attendance-detail__table-data">
                        <input class="attendance-detail__form-input" type="text" name="work_start" value="{{substr($work->start_time,0,5)}}">
                        <span class="attendance-detail__table-span">~</span>
                        <input class="attendance-detail__form-input" type="text" name="work_end" value="{{substr($work->end_time,0,5)}}"></td>
                    @else
                    <td class="attendance-detail__table-data">{{substr($work->workCorrection->work_start,0,5)}}
                    <span class="attendance-detail__table-span">~</span>
                    {{substr($work->workCorrection->work_end,0,5)}}</td>
                    @endif
                </tr>
                @if($work->restCorrections->isEmpty() && $work->workCorrection === null || $work->workCorrection->status === 2)
                    @foreach($work->rests as $rest)
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-heading">休憩</th>
                        <td class="attendance-detail__table-data">
                            <input class="attendance-detail__form-input" type="text" name="rest_start[]" value="{{substr($rest->rest_start,0,5)}}">
                            <span class="attendance-detail__table-span">~</span>
                            <input class="attendance-detail__form-input" type="text" name="rest_end[]" value="{{substr($rest->rest_end,0,5)}}">
                            <input type="hidden" name="rest_id[]" value="{{$rest->id}}">
                        </td>
                    </tr>
                    @endforeach
                @elseif($work->restCorrections->isEmpty() && $work->workCorrection !== null)
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">休憩</th>
                    <td class="attendance-detail__table-data"></td>
                </tr>
                @else
                    @foreach($work->restCorrections as $restCorrection)
                        <tr class="attendance-detail__table-row">
                            <th class="attendance-detail__table-heading">休憩</th>
                            <td class="attendance-detail__table-data">{{substr($restCorrection->rest_start,0,5)}}
                            <span class="attendance-detail__table-span">~</span>
                            {{substr($restCorrection->rest_end,0,5)}}</td>
                        </tr>
                    @endforeach
                @endif
                @if($work->workCorrection === null || $work->workCorrection->status === 2)
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">休憩</th>
                    <td class="attendance-detail__table-data"><input class="attendance-detail__form-input" type="text" name="rest_start2">
                    <span class="attendance-detail__table-span">~</span>
                    <input class="attendance-detail__form-input" type="text" name="rest_end2"></td>
                </tr>
                 @endif
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">備考</th>
                    @if(empty($work->workCorrection) || $work->workCorrection->status === 2)
                        <td class="attendance-detail__table-data"><textarea class="attendance-detail__form-textarea" name="note"></textarea></td>
                    @else
                        <td class="attendance-detail__table-data">{{$work->workCorrection->note}}</td>
                    @endif
                </tr>
            </table>
            <div class="attendance-detail__form-button">
                @if(empty($work->workCorrection) || $work->workCorrection->status === 2)
                    <button class="attendance-detail__form-submit">修正</button>
                @else
                    <p class="attendance-detail__form-message">*承認待ちのため修正はできません</p>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection