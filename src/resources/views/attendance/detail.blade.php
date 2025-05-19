@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/attendance_detail.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>勤怠詳細</h2>
    </div>
    <div>
        <form action="/attendance" method="post">
        @csrf
            <input type="hidden" name="work_id" value="{{$work->id}}">
            <input type="hidden" name="status" value="1">
            <table>
                <tr>
                    <th>名前</th>
                    <td>{{$work->user->name}}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{\Carbon\Carbon::parse($work->date)->format('Y年')}}
                   {{\Carbon\Carbon::parse($work->date)->format('n月j日')}}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    @if($work->workCorrection === null)
                    <td>
                        <input type="text" name="work_start" value="{{substr($work->start_time,0,5)}}">~<input type="text" name="work_end" value="{{substr($work->end_time,0,5)}}"></td>
                    @else
                    <td>{{substr($work->workCorrection->work_start,0,5)}}~{{substr($work->workCorrection->work_end,0,5)}}</td>
                    @endif
                </tr>
                @if($work->restCorrections->isEmpty() && $work->workCorrection === null)
                @foreach($work->rests as $rest)
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="text" name="rest_start[]" value="{{substr($rest->rest_start,0,5)}}">~
                        <input type="text" name="rest_end[]" value="{{substr($rest->rest_end,0,5)}}">
                         <input type="hidden" name="rest_id[]" value="{{$rest->id}}">
                    </td>
                </tr>
                @endforeach
                @elseif($work->restCorrections->isEmpty() && $work->workCorrection !== null)
                <tr>
                    <th>休憩</th>
                    <td></td>
                </tr>
                @else
                @foreach($work->restCorrections as $restCorrection)
                    <tr>
                        <th>休憩</th>
                        <td>{{substr($restCorrection->rest_start,0,5)}}~{{substr($restCorrection->rest_end,0,5)}}</td>
                    </tr>
                @endforeach
                @endif
                @if($work->workCorrection === null)
                <tr>
                    <th>休憩</th>
                    <td><input type="text" name="rest_start2">~<input type="text" name="rest_end2"></td>
                </tr>
                 @endif
                <tr>
                    <th>備考</th>
                    @if(empty($work->workCorrection))
                    <td><textarea name="note"></textarea></td>
                    @else
                    <td>{{$work->workCorrection->note}}</td>
                    @endif
                </tr>
            </table>
            <div>
                @if(empty($work->workCorrection))
                <button>修正</button>
                @else
                    <p>承認待ちのため修正はできません</p>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection