@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff_list.css')}}">
@endsection

@section('content')
<div>
    <div>
        <h2>スタッフ一覧</h2>
    </div>
    <div>
        <table>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td><a href="/admin/attendance/staff/{{$user->id}}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection