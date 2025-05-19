@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/timestamp.css')}}">
@endsection

@section('content')
    @if($work === null)
    <p>勤務外</p>
    @elseif($work->status == '1')
    <p>勤務中</p>
    @elseif($work->status == '2')
    <p>休憩中</p>
    @elseif($work->status== '3')
    <p>退勤済</p>
    @endif
    <div>
         <p>{{$now->translatedFormat('Y年n月j日(D)')}}</p>
        <p>{{$now->format('H:i')}}</p>
    </div>
    <div>
        @if($work === null)
        <a href="/timestamp/punch_in">出勤</a>
        @elseif($work->status == '1')
        <a href="/timestamp/punch_out">退勤</a>
        <a href="/timestamp/break_begins">休憩入</a>
        @elseif($work->status == '2')
        <a href="/timestamp/break_ends">休憩戻</a>
        @elseif($work->status == '3')
        <p>お疲れ様でした。</p>
        @endif
    </div>
@endsection