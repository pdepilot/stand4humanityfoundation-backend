<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We received your report {{ $report->reference }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;color:#1c2340;font-family:Georgia,'Times New Roman',serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border:1px solid #e4e8f4;">
                    <tr>
                        <td style="background:#0020b0;padding:28px 32px;">
                            <img src="{{ $message->embed(public_path('images/portal/stand-logo.png')) }}" alt="Stand For Humanity Foundation" width="72" height="72" style="display:block;border:0;">
                            <p style="margin:16px 0 0;color:#ffac00;font-family:Arial,Helvetica,sans-serif;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;">Report received</p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:28px;line-height:1.25;font-weight:normal;">Your case is on the desk</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:6px;background:#ffac00;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px 12px;">
                            <p style="margin:0 0 18px;font-size:18px;">Hello {{ $greetingName }},</p>
                            <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">We received your {{ $report->incident_type->label() }} report, “{{ $report->incident_title }}”. The desk has it as <strong>{{ $report->status->label() }}</strong>.</p>
                            <p style="margin:0 0 8px;font-family:Arial,Helvetica,sans-serif;font-size:13px;letter-spacing:0.12em;text-transform:uppercase;color:#0020b0;">Your reference code</p>
                            <p style="margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;font-size:28px;letter-spacing:0.04em;color:#0020b0;">{{ $report->reference }}</p>
                            <p style="margin:0 0 16px;font-size:17px;line-height:1.6;">Keep this code. Open the report page and enter it under “Track your report” to follow the progress of the case.</p>
                            <p style="margin:24px 0 0;font-size:17px;line-height:1.6;">With care,<br>The foundation desk<br>Owerri, Imo State</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 28px;">
                            <a href="{{ $trackingUrl }}" style="display:inline-block;background:#0020b0;color:#ffffff;text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:14px;padding:12px 18px;">Track this report</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#0d1433;color:#d5dcf0;padding:24px 32px;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.6;">
                            <strong style="color:#ffffff;">Stand For Humanity Foundation</strong><br>
                            {{ $siteProfile->address }}<br>
                            {{ $siteProfile->phone }} · {{ $siteProfile->email }}<br>
                            <a href="{{ route('privacy') }}" style="color:#ffac00;">Privacy</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
