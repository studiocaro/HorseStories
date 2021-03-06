@extends('layout.app')

@section('content')
    <h2>{{ $conversation->subject }}</h2>
    @if (count($messages))
        @foreach ($messages as $message)
            @if ($message->user == Auth::user())
                <p style="color:red;">{{ nl2br($message->body) }}</p><span>{{ $message->created_at->diffForHumans() }}</span>
            @else
                <p>{{ nl2br($message->body) }}</p><span>{{ $message->created_at->diffForHumans() }}</span>
            @endif
        @endforeach
    @else
        <p>{{ trans('copy.p.empty_conversation') }}</p>
    @endif

    {{ Form::open(['route' => ['message.store', $conversation->id]]) }}
        {{ Form::label('message', trans('forms.labels.reply')) }}
        {{ Form::textarea('message', null, ['class' => 'materialize-textarea']) }}
        {{ Form::submit(trans('forms.buttons.reply'), ['class' => 'btn']) }}
    {{ Form::close() }}
@stop
