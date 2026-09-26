{{ $template->label() }}
Stand For Humanity Foundation

Hello{{ $recipientName ? ' '.$recipientName : '' }},

@foreach ($paragraphs as $paragraph)
{{ $paragraph }}

@endforeach
With care,
The foundation desk
Owerri, Imo State

Visit the foundation: {{ route('home') }}

Stand For Humanity Foundation
{{ $siteProfile->address }}
{{ $siteProfile->phone }}
{{ $siteProfile->email }}
@if ($audience === \App\Enums\MailAudience::Subscribers)
You are receiving this because you subscribed to programme notes.
@endif
