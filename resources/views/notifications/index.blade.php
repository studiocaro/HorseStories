@extends('layout.app')

@section('content')
    <div class="row">
        <a href="{{ route('notifications.mark-read') }}">{{ trans('copy.a.mark_as_read') }}</a>
        <ul>
            @foreach ($notifications as $notification)
                <li><a href="{{ $notification->link }}">{{ trans('notifications.' . $notification->type, json_decode($notification->data, true)) }}</a></li>
            @endforeach
        </ul>
    </div>
@stop
