@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/timestamp.css')}}">
@endsection

@section('content')
<div class="timestamp">
    <div class="timestamp__status">
    @if($work === null)
        <span class="timestamp__status-span">勤務外</span>
    @elseif($work->status == '1')
        <span class="timestamp__status-span">勤務中</span>
    @elseif($work->status == '2')
        <span class="timestamp__status-span">休憩中</span>
    @elseif($work->status== '3')
        <span class="timestamp__status-span">退勤済</span>
    @endif
    </div>
    <div class="timestamp__now">
         <p class="timestamp__now-date">{{$now->translatedFormat('Y年n月j日(D)')}}</p>
        <p class="timestamp__now-time">{{$now->format('H:i')}}</p>
    </div>
    <div class="timestamp__condition">
        @if($work === null)
        <a href="/timestamp/punch_in" class="timestamp__condition-working">出勤</a>
        @elseif($work->status == '1')
        <div class="timestamp__condition-flex">
            <a href="/timestamp/punch_out" class="timestamp__condition-leaving">退勤</a>
            <a href="/timestamp/break_begins" class="timestamp__condition-breaking">休憩入</a>
        </div>
        @elseif($work->status == '2')
        <a href="/timestamp/break_ends" class="timestamp__condition-breaking-back">休憩戻</a>
        @elseif($work->status == '3')
        <p class="timestamp__good-job">お疲れ様でした。</p>
        @endif
    </div>
</div>
@endsection