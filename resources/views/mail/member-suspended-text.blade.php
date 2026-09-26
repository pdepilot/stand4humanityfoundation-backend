Membership suspended
Stand For Humanity Foundation

Hello {{ $member->full_name }},

@if ($forAbsence)
You missed three Saturday meetings in a row.
Your membership of Stand For Humanity Foundation is now suspended.
@else
The foundation desk has suspended your membership of Stand For Humanity Foundation.
@endif

Member number
{{ $member->member_number }}

@if ($forAbsence)
Meetings are marked present on Saturdays from 9:00 to 10:00.
Write to the desk if you want this suspension reviewed.
@else
Write to the desk if you want this suspension reviewed.
@endif

With care,
The foundation desk
Owerri, Imo State

Stand For Humanity Foundation
{{ $siteProfile->address }}
{{ $siteProfile->phone }}
{{ $siteProfile->email }}
{{ route('contact') }}
