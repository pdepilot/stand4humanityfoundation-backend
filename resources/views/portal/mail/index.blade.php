<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Email center | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-donors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-news.css') }}">
</head>
<body data-page="mail">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'mail'])
        <div class="stage">
            <header class="stage-head compact settings-hero">
                <div class="settings-media" aria-hidden="true">
                    <img src="{{ asset('images/portal/stand4.jpg') }}" alt="">
                    <div class="settings-multiply"></div>
                    <div class="settings-color"></div>
                    <div class="settings-gold"></div>
                </div>
                <div class="head-top">
                    <div>
                        <p class="eyebrow">Command · Email center</p>
                        <h1 class="head-title">Mail</h1>
                        <p class="head-copy">Send a branded note to one person or a foundation group. Each person receives their own copy.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Compose">
                        <div class="panel-title">
                            <div>
                                <h2>Compose</h2>
                                <p>Logo, colours, and footer are added for you</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif
                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('portal.mail.store') }}" class="settings-form" enctype="multipart/form-data" data-mail-form>
                            @csrf
                            <div class="settings-grid">
                                <div class="settings-field">
                                    <label for="template">Template</label>
                                    <select id="template" name="template" required>
                                        @foreach ($templates as $template)
                                            <option value="{{ $template->value }}" @selected(old('template', $template->value) === $template->value)>{{ $template->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="settings-field">
                                    <label for="audience">Send to</label>
                                    <select id="audience" name="audience" required data-mail-audience>
                                        @foreach ($audiences as $audience)
                                            <option value="{{ $audience->value }}" @selected(old('audience', 'individual') === $audience->value)>{{ $audience->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="settings-grid" data-mail-individual>
                                <div class="settings-field">
                                    <label for="recipient_name">Recipient name</label>
                                    <input id="recipient_name" name="recipient_name" type="text" value="{{ old('recipient_name') }}">
                                </div>
                                <div class="settings-field">
                                    <label for="recipient_email">Recipient email</label>
                                    <input id="recipient_email" name="recipient_email" type="email" value="{{ old('recipient_email') }}">
                                </div>
                            </div>
                            <div class="settings-field">
                                <label for="subject">Subject</label>
                                <input id="subject" name="subject" type="text" value="{{ old('subject', $templates[0]->subject()) }}" required data-mail-subject>
                            </div>
                            <div class="settings-field">
                                <label for="body">Message</label>
                                <textarea id="body" name="body" rows="8" required data-mail-body>{{ old('body', $templates[0]->body()) }}</textarea>
                            </div>
                            <div class="settings-field">
                                <label for="attachments">Documents</label>
                                <input id="attachments" name="attachments[]" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                                <div class="news-count-line"><span>Up to three files · PDF, Word, or image · 5 MB each</span></div>
                            </div>
                            <button class="settings-submit" type="submit" data-mail-send>Send message</button>
                        </form>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Sent">
                        <div class="panel-title">
                            <div>
                                <h2>Recent sends</h2>
                                <p>The last notes from this desk</p>
                            </div>
                        </div>
                        @if ($messages->isEmpty())
                            <p class="news-form-hint">No messages have been sent yet.</p>
                        @else
                            <div class="news-quality">
                                @foreach ($messages as $message)
                                    <div class="news-quality-item">
                                        <span class="news-quality-mark">{{ $message->recipient_count }}</span>
                                        <div>
                                            <strong>{{ $message->subject }}</strong>
                                            <span>{{ $message->audience->label() }} · {{ $message->sent_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </aside>
                </div>
            </main>
        </div>
    </div>
    @php
        $templateCopy = collect($templates)->mapWithKeys(fn ($template) => [
            $template->value => [
                'subject' => $template->subject(),
                'body' => $template->body(),
            ],
        ]);
    @endphp
    <div id="mail-templates" hidden data-templates="{{ \Illuminate\Support\Js::from($templateCopy) }}"></div>
    <script>
        const templates = JSON.parse(document.getElementById('mail-templates').getAttribute('data-templates'));
        const templateField = document.querySelector('#template');
        const subjectField = document.querySelector('[data-mail-subject]');
        const bodyField = document.querySelector('[data-mail-body]');
        const audienceField = document.querySelector('[data-mail-audience]');
        const individualFields = document.querySelector('[data-mail-individual]');

        templateField?.addEventListener('change', function () {
            const chosen = templates[templateField.value];
            if (!chosen) {
                return;
            }
            subjectField.value = chosen.subject;
            bodyField.value = chosen.body;
        });

        function toggleIndividual() {
            const show = audienceField.value === 'individual';
            individualFields.hidden = !show;
        }

        audienceField?.addEventListener('change', toggleIndividual);
        toggleIndividual();

        const mailForm = document.querySelector('[data-mail-form]');
        const sendButton = document.querySelector('[data-mail-send]');

        mailForm?.addEventListener('submit', function () {
            if (!sendButton) {
                return;
            }

            sendButton.disabled = true;
            sendButton.textContent = 'Sending…';
        });
    </script>
</body>
</html>
