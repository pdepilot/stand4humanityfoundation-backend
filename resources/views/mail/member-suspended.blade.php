<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your membership has been suspended</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;color:#1c2340;font-family:Georgia,'Times New Roman',serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border:1px solid #e4e8f4;">
                    <tr>
                        <td style="background:#0020b0;padding:28px 32px;">
                            <img src="{{ $message->embed(public_path('images/portal/stand-logo.png')) }}" alt="Stand For Humanity Foundation" width="72" height="72" style="display:block;border:0;">
                            <p style="margin:16px 0 0;color:#ffac00;font-family:Arial,Helvetica,sans-serif;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;">Membership</p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:28px;line-height:1.25;font-weight:normal;">Your membership is suspended</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:6px;background:#ffac00;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px 12px;">
                            <p style="margin:0 0 18px;font-size:18px;">Hello {{ $member->full_name }},</p>
                            @if ($forAbsence)
                                <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">You missed three Saturday meetings in a row. Your membership of Stand For Humanity Foundation is now suspended.</p>
                            @else
                                <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">The foundation desk has suspended your membership of Stand For Humanity Foundation.</p>
                            @endif
                            <p style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:13px;letter-spacing:0.12em;text-transform:uppercase;color:#0020b0;">Member number</p>
                            <p style="margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;font-size:28px;letter-spacing:0.04em;color:#0020b0;">{{ $member->member_number }}</p>
                            @if ($forAbsence)
                                <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">Meetings are marked present on Saturdays from 9:00 to 10:00. Write to the desk if you want this suspension reviewed.</p>
                            @else
                                <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">Write to the desk if you want this suspension reviewed.</p>
                            @endif
                            <p style="margin:24px 0 0;font-size:17px;line-height:1.6;">With care,<br>The foundation desk<br>Owerri, Imo State</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#0d1433;color:#d5dcf0;padding:24px 32px;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.6;">
                            <strong style="color:#ffffff;">Stand For Humanity Foundation</strong><br>
                            {{ $siteProfile->address }}<br>
                            {{ $siteProfile->phone }} · {{ $siteProfile->email }}<br>
                            <a href="{{ route('contact') }}" style="color:#ffac00;">Contact</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
