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
            @foreach($corrections as $correction)
            <tr>
                @if($correction->status == '1')
                <td>承認待ち</td>
                @elseif($correction->status == '2')
                <td>承認済み</td>
                @endif
                <td>{{$correction->user->name}}</td>
                <td>{{$correction->work->date}}</td>
                <td>{{$correction->note}}</td>
                <td>{{$correction->created_at}}</td>
                <td><a href="/attendance/{{$correction->work->id}}">詳細</a></td>
                <!-- if文でadminのidがあったら　として処理を変える -->
                <td>
                    <a href="/stamp_correction_request/approve/{{$correction->id}}">修正申請承認ページへ</a>
                </td>
                
            </tr>
            @endforeach
        </table>
        
    </div>
@endsection