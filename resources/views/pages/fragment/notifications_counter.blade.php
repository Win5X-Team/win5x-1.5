@php
    $count = \App\Notification::where('user_id', Auth::user()->id)->where('read_status', 0)->count();
@endphp
@if($count > 0)
    <div class="notifications_icon">{{ $count }}</div>
@else
    <div></div>
@endif