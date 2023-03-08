@component('mail::message')
# Introduction

Thank you {{ $order->name }}.  We just shipped {{ $order->item_count }} items.

@component('mail::button', ['url' => ''])
Track Order #{{ $order->id }}
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent 