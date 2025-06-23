@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/approval.css')}}">
@endsection

@section('content')
<div class="approval">
    <h2 class="approval__ttl">勤怠詳細</h2>
    <div class="approval__content">
        <form class="approval__form" action="/stamp_correction_request/approve" method="post">
        @csrf
            <table class="approval__table">
                <tr class="approval__table-row">
                    <th class="approval__table-heading">名前</th>
                    <td class="approval__table-data">{{$work_correction->user->name}}</td>
                </tr>
                <tr class="approval__table-row">
                    <th class="approval__table-heading">日付</th>
                    <td class="approval__table-data">
                        <span class="approval__span-year">{{ \Carbon\Carbon::parse($work_correction->work->date)->format('Y年')}}</span>
                        <span class="approval__span-day">{{ \Carbon\Carbon::parse($work_correction->work->date)->format('n月j日')}}</span>
                    </td>
                </tr>
                <tr class="approval__table-row">
                    <th class="approval__table-heading">出勤・退勤</th>
                    <td class="approval__table-data">{{substr($work_correction->work_start,0,5)}}
                        <span class="approval__table-span">~</span>
                        {{substr($work_correction->work_end,0,5)}}
                    </td>
                    <input type="hidden" name="work_start" value="{{$work_correction->work_start}}">
                    <input type="hidden" name="work_end" value="{{$work_correction->work_end}}">
                </tr>
                @foreach($rest_corrections as $rest_correction)
                    <tr class="approval__table-row">
                        <th class="approval__table-heading">休憩</th>
                        <td class="approval__table-data">
                            {{substr($rest_correction->rest_start,0,5)}}
                            <span class="approval__table-span">~</span>{{substr($rest_correction->rest_end,0,5)}}
                        </td>
                        <input type="hidden" name="rest_start[]" value="{{$rest_correction->rest_start}}">
                        <input type="hidden" name="rest_end[]" value="{{$rest_correction->rest_end}}">
                        <input type="hidden" name="rest_id[]" value="{{$rest_correction->rest_id}}">
                    </tr>
                @endforeach
                <tr class="approval__table-row">
                    <th class="approval__table-heading">備考</th>
                    <td class="approval__table-data">{{$work_correction->note}}</td>
                </tr>
            </table>
            <div class="approval__form-button">
                <input type="hidden" name="work_id" value="{{$work_correction->work_id}}">
                @if($work_correction->status === 1)
                    <button class="approval__form-submit">承認</button>
                @else
                    <span class="approval__form-status">承認済み</span>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection