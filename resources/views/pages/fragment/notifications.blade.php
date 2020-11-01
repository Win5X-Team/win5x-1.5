@foreach(\App\Notification::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get() as $notification)
    <div class="header_notification notify-{{$notification->type}}">
        <div class="notify-header">
            <div class="notify-title">
                <span class="tooltip" title="{{ \App\Http\Controllers\SportController::formatDate($notification->time) }}">
                    <i class="{{$notification->icon}}"></i>
                    {!! $notification->title !!}
                </span>
            </div>
        </div>
        <div class="notify-description">
            {!! $notification->message !!}
        </div>
    </div>
@endforeach