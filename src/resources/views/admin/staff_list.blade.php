@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin/staff_list.css')}}">
@endsection

@section('content')
<div class="staff-list">
    <h2 class="staff-list__ttl">スタッフ一覧</h2>
    <div class="staff-list__content">
        <table class="staff-list__table">
            <tr class="staff-list__table-row">
                <th class="staff-list__table-heading">名前</th>
                <th class="staff-list__table-heading">メールアドレス</th>
                <th class="staff-list__table-heading">月次勤怠</th>
            </tr>
            @foreach($users as $user)
                <tr class="staff-list__table-row">
                    <td class="staff-list__table-data">{{$user->name}}</td>
                    <td class="staff-list__table-data">{{$user->email}}</td>
                    <td class="staff-list__table-data"><a class="staff-list__table-link" href="/admin/attendance/staff/{{$user->id}}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection