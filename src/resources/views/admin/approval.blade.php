@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/approval.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>勤怠詳細</h2>
    </div>
    <div>
        <form action="/stamp_correction_request/approve" method="post">
            @csrf
            <table>
                <tr>
                    <th>名前</th>
                    <td>{{$work_correction->user->name}}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{ \Carbon\Carbon::parse($work_correction->work->date)->format('Y年')}}
                    {{ \Carbon\Carbon::parse($work_correction->work->date)->format('n月j日')}}
                    </td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>{{substr($work_correction->work_start,0,5)}}~{{substr($work_correction->work_end,0,5)}}</td>
                    <input type="hidden" name="start_time" value="{{$work_correction->work_start}}">
                    <input type="hidden" name="end_time" value="{{$work_correction->work_end}}">
                </tr>
                @foreach($rest_corrections as $rest_correction)
                <tr>
                    <th>休憩</th>
                    <td>{{substr($rest_correction->rest_start,0,5)}}~{{substr($rest_correction->rest_end,0,5)}}</td>
                    <input type="hidden" name="rest_start[]" value="{{$rest_correction->rest_start}}">
                    <input type="hidden" name="rest_end[]" value="{{$rest_correction->rest_end}}">
                    <input type="hidden" name="rest_id[]" value="{{$rest_correction->rest_id}}">
                </tr>
                @endforeach
                <!-- <tr>
                    <th>休憩2</th>
                    <td></td>
                </tr> -->
                <tr>
                    <th>備考</th>
                    <td>{{$work_correction->note}}</td>
                </tr>
            </table>
            <div>
                <input type="hidden" name="work_id" value="{{$work_correction->work_id}}">
                @if($work_correction->status === 1)
                <button>承認</button>
                @else
                <span>承認済み</span>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection