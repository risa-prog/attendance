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
                    <td>{{$work->date}}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" name="punchIn" value="{{$work->start_time}}">~
                        <input type="text" name="punchOut" value="{{$work->end_time}}">
                    </td>
                </tr>
                @foreach($work->rests as $rest)
                <tr>
                    <th>休憩</th>
                    <td>
                        <input type="text" name="break_begins[]" value="{{$rest->start_time}}">~
                        <input type="text" name="break_ends[]" value="{{$rest->end_time}}">
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th>休憩2</th>
                    <td><input type="text" name="break_begins[]">~<input type="text" name="break_ends"></td>
                </tr>
                <tr>
                    <th>備考</th>
                    @if(empty($work->correction))
                    <td><textarea name="note"></textarea></td>
                    @else
                    <td>{{optional($work->correction)->note}}</td>
                    @endif
                </tr>
            </table>
            <div>
                @if(empty($work->correction))
                <button>修正</button>
                @else
                    <p>承認待ちのため修正はできません</p>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection