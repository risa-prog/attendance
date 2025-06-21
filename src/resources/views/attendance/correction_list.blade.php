@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance/correction.css')}}">
@endsection

@section('content')
<div class="correction">
    <h2 class="correction__ttl">申請一覧</h2>
    <div class="correction__status">
        <a class="correction__link" href="/stamp_correction_request/list?tab=waiting_for_approval">承認待ち</a>
        <a class="correction__link" href="/stamp_correction_request/list?tab=approved">承認済み</a>
    </div>
    <div class="correction__content">
        <table class="correction__table">
            <tr class="correction__table-row">
                <th class="correction__table-heading">状態</th>
                <th class="correction__table-heading">名前</th>
                <th class="correction__table-heading">対象日時</th>
                <th class="correction__table-heading">申請理由</th>
                <th class="correction__table-heading">申請日時</th>
                <th class="correction__table-heading">詳細</th>
            </tr>
            @foreach($work_corrections as $work_correction)
                <tr class="correction__table-row">
                    @if($work_correction->status == '1')
                        <td class="correction__table-data">承認待ち</td>
                    @elseif($work_correction->status == '2')
                        <td class="correction__table-data">承認済み</td>
                    @endif
                    <td class="correction__table-data">{{$work_correction->work->user->name}}</td>
                    <td class="correction__table-data">{{\Carbon\Carbon::parse($work_correction->work->date)->format('Y/m/d')}}</td>
                    <td class="correction__table-data">{{$work_correction->note}}</td>
                    <td class="correction__table-data">{{\Carbon\Carbon::parse($work_correction->created_at)->format('Y/m/d')}}</td>
                    <td class="correction__table-data">
                    @if (Auth::guard('web')->check())
                        <a class="correction__table-link" href="/attendance/{{$work_correction->work->id}}">詳細</a>
                    @elseif (Auth::guard('admin')->check())
                        <a class="correction__table-link" href="/stamp_correction_request/approve/{{$work_correction->id}}">詳細</a>
                    @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection