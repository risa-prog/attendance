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
        <span class="timestamp__status-span">出勤中</span>
    @elseif($work->status == '2')
        <span class="timestamp__status-span">休憩中</span>
    @elseif($work->status== '3')
        <span class="timestamp__status-span">退勤済</span>
    @endif
    </div>
    <div class="timestamp__now">
         <p class="timestamp__now-date">{{$now->translatedFormat('Y年n月j日(D)')}}</p>
        <p class="timestamp__now-time" id="clock"></p>
    </div>
    <div class="timestamp__condition">
        @if($work === null)
            <form action="/timestamp/work_start" method="post">
                @csrf
                <button class="timestamp__condition-working">出勤</button>
            </form>
        @elseif($work->status == '1')
            <div class="timestamp__condition-flex">
                <form action="/timestamp/work_end" method="post">
                    @csrf
                    <button class="timestamp__condition-leaving">退勤</button>
                </form>
                <form action="/timestamp/rest_start" method="post">
                    @csrf
                    <button class="timestamp__condition-rest-start">休憩入</button>
                </form>
            </div>
        @elseif($work->status == '2')
            <form action="/timestamp/rest_end" method="post">
                @csrf
                <button class="timestamp__condition-rest-end">休憩戻</button>
            </form>
        @elseif($work->status == '3')
        <p class="timestamp__good-job">お疲れ様でした。</p>
        @endif
    </div>
</div>
<script>
        function updateTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();

            // 2桁表示（例：09:05）
            hours = hours.toString().padStart(2, '0');
            minutes = minutes.toString().padStart(2, '0');

            document.getElementById('clock').innerText = `${hours}:${minutes}`;
        }

        // ページ読み込み時に実行し、1分ごとに更新
        window.onload = function () {
            updateTime();
            setInterval(updateTime, 60000); // 1分ごとに更新
        };
</script>
@endsection