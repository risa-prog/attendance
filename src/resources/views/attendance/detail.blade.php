@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('css/attendance/detail.css')}}">
@endsection

@section('content')
<div class="attendance-detail">
    <h2 class="attendance-detail__ttl">勤怠詳細</h2>
    <div class="attendance-detail__content">
        @php
        if (Auth::guard('web')->check()) {
            $actionUrl = url('/attendance/correct_request');
            } elseif (Auth::guard('admin')->check()) {
            $actionUrl = url('/stamp_correction');
            }
        @endphp

        <form class="attendance-detail__form" action="{{ $actionUrl }}" method="post">
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
                            {{\Carbon\Carbon::parse($work->date)->format('n月j日')}}
                        </span>
                    </td>
                </tr>
                @if(empty($work->workCorrection) || Auth::guard('admin')->check())
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">出勤・退勤</th>
                    <td class="attendance-detail__table-data">
                        <input class="attendance-detail__form-input" type="text" name="work_start" value="{{old('work_start',substr($work->work_start,0,5))}}">
                        <span class="attendance-detail__table-span">~</span>
                        <input class="attendance-detail__form-input" type="text" name="work_end" value="{{old('work_end',substr($work->work_end,0,5))}}">
                        <div class="error">
                            @error('work_start')
                            <span class="error-message">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="error-message">
                            @error('work_end')
                            <span class="error-message">{{$message}}</span>
                            @enderror
                        </div>
                    </td>
                </tr>
                @else
                <tr class="attendance-detail__table-row">
                    <th class="attendance-detail__table-heading">出勤・退勤</th>
                    <td class="attendance-detail__table-data">
                        {{substr($work->workCorrection->work_start,0,5)}}
                        <span class="attendance-detail__table-span">~</span>
                        {{substr($work->workCorrection->work_end,0,5)}}
                    </td>
                </tr>
                @endif

                @if(empty($work->workCorrection) || Auth::guard('admin')->check())
                @for($i = 0; $i < $rests->count(); $i++)
                    @php
                    $rest = $rests[$i];
                    @endphp
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-heading">休憩</th>
                        <td class="attendance-detail__table-data">
                            <input class="attendance-detail__form-input" type="text" name="rest_start[{{ $i }}]" value="{{ old("rest_start.$i",substr($rest->rest_start,0,5)) }}">
                            <span class="attendance-detail__table-span">~</span>
                            <input class="attendance-detail__form-input" type="text" name="rest_end[{{$i}}]" value="{{old("rest_end.$i",substr($rest->rest_end,0,5))}}">
                            <input type="hidden" name="rest_id[{{$i}}]" value="{{$rest->id}}">
                            @error("rest_start.$i")
                            <div class="error">
                                <span class="error-message">{{ $message }}</span>
                            </div>
                            @enderror
                            @error("rest_end.$i")
                            <div class="error">
                                <span class="error-message">{{ $message }}</span>
                            </div>
                            @enderror
                        </td>
                    </tr>
                    @endfor
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-heading">休憩</th>
                        <td class="attendance-detail__table-data">
                            <input class="attendance-detail__form-input" type="text" name="rest_start[{{$i}}]" value="{{old("rest_start.$i")}}">
                            <span class="attendance-detail__table-span">~</span>
                            <input class="attendance-detail__form-input" type="text" name="rest_end[{{$i}}]" value="{{old("rest_end.$i")}}">
                            <input type="hidden" name="rest_id[{{$i}}]" value="">
                            @error("rest_start.$i")
                            <div class="error">
                                <span class="error-message">{{ $message }}</span>
                            </div>
                            @enderror
                            @error("rest_end.$i")
                            <div class="error">
                                <span class="error-message">{{ $message }}</span>
                            </div>
                            @enderror
                        </td>
                    </tr>
                    @else
                    
                    @foreach($work->restCorrections as $restCorrection)
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-heading">休憩</th>
                        <td class="attendance-detail__table-data">
                            {{substr($restCorrection->rest_start,0,5)}}
                            <span class="attendance-detail__table-span">~</span>
                            {{substr($restCorrection->rest_end,0,5)}}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-heading">備考</th>
                        @if(empty($work->workCorrection) || Auth::guard('admin')->check())
                        <!-- 修正していない、あるいは管理者の時 -->
                        <td class="attendance-detail__table-data">
                            <textarea name="note" class="attendance-detail__form-textarea" id="note">{{old('note')}}</textarea>
                            @error('note')
                            <div class="error">
                                <span class="error-message">{{$message}}</span>
                            </div>
                            @enderror
                        </td>
                        @else
                        <td class="attendance-detail__table-data"> {{$work->workCorrection->note}}
                        </td>
                        @endif
                    </tr>
            </table>
            @if (Auth::guard('web')->check())
            <div class="attendance-detail__form-button">
                @if(empty($work->workCorrection))
                <button class="attendance-detail__form-submit">修正</button>
                @elseif($work->workCorrection->status === 2)
                <p class="attendance-detail__form-message">承認済みです</p>
                @else
                <p class="attendance-detail__form-message">*承認待ちのため修正はできません</p>
                @endif
            </div>
            @elseif (Auth::guard('admin')->check())
            <div class="attendance-detail__form-button">
                <button class="attendance-detail__form-submit">修正</button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection