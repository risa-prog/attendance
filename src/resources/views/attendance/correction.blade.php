@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <div>
        <h2>申請一覧</h2>
    </div>
    <div>
        <a href="/waiting_for_approval">承認待ち</a>
        <a href="/approved">承認済み</a>
    </div>
    <div>
        <table>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
                <th>admin</th>
            </tr>
            @foreach($work_corrections as $work_correction)
            <tr>
                @if($work_correction->status == '1')
                <td>承認待ち</td>
                @elseif($work_correction->status == '2')
                <td>承認済み</td>
                @endif
                <td>{{$work_correction->user->name}}</td>
                <td>{{\Carbon\Carbon::parse($work_correction->work->date)->format('Y/m/d')}}</td>
                <td>{{$work_correction->note}}</td>
                <td>{{\Carbon\Carbon::parse($work_correction->created_at)->format('Y/m/d')}}</td>
                <td><a href="/attendance/{{$work_correction->work->id}}">詳細</a></td>
                <!-- if文でadminのidがあったら　として処理を変える -->
                <td>
                    <a href="/stamp_correction_request/approve/{{$work_correction->id}}">修正申請承認ページへ</a>
                </td>
                
            </tr>
            @endforeach
        </table>
        
    </div>
@endsection