<?php

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationPaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidentReportController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\Portal\AdminRoleController;
use App\Http\Controllers\Portal\AdminUserController;
use App\Http\Controllers\Portal\AuditReportController;
use App\Http\Controllers\Portal\AuthenticatedSessionController;
use App\Http\Controllers\Portal\CaseController;
use App\Http\Controllers\Portal\ContactController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DonationGoalController;
use App\Http\Controllers\Portal\DonorController;
use App\Http\Controllers\Portal\EventController as PortalEventController;
use App\Http\Controllers\Portal\GalleryItemController;
use App\Http\Controllers\Portal\LoginBanController;
use App\Http\Controllers\Portal\MailCenterController;
use App\Http\Controllers\Portal\MeetingAttendanceController;
use App\Http\Controllers\Portal\MemberController;
use App\Http\Controllers\Portal\NewsController as PortalNewsController;
use App\Http\Controllers\Portal\NewsletterSubscriberController as PortalNewsletterSubscriberController;
use App\Http\Controllers\Portal\NotificationController;
use App\Http\Controllers\Portal\SettingsController;
use App\Http\Controllers\Portal\SiteProfileController;
use App\Http\Controllers\Portal\TeamMemberController;
use App\Http\Controllers\Portal\TestimonyController as PortalTestimonyController;
use App\Http\Controllers\Portal\VolunteerController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\VolunteerApplicationController;
use App\Http\Middleware\EnsurePortalAccountIsActive;
use App\Http\Middleware\EnsurePortalPermission;
use App\Http\Middleware\ExpireIdlePortalSession;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::view('/about', 'site.about')->name('about');
Route::view('/services', 'site.services')->name('services');
Route::view('/donations', 'site.donations')->name('donations');
Route::view('/donate', 'site.donate')->name('donate');
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{galleryItem}', [GalleryController::class, 'show'])->name('gallery.show');
Route::view('/volunteer', 'site.volunteer')->name('volunteer');
Route::view('/our-stand', 'site.our-stand')->name('our-stand');
Route::view('/report', 'site.report')->name('report');
Route::view('/mission', 'site.mission')->name('mission');
Route::view('/contact', 'site.contact')->name('contact');
Route::view('/help', 'site.help')->name('help');
Route::view('/privacy', 'site.privacy')->name('privacy');
Route::view('/terms', 'site.terms')->name('terms');

Route::get('/api/donations', [DonationController::class, 'index'])->name('donations.api.index');
Route::post('/api/donations', [DonationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('donations.api.store');
Route::get('/donations/paystack/callback', [DonationPaymentController::class, 'paystack'])->name('donations.paystack.callback');
Route::get('/donations/flutterwave/callback', [DonationPaymentController::class, 'flutterwave'])->name('donations.flutterwave.callback');
Route::post('/webhooks/paystack', [DonationPaymentController::class, 'paystackWebhook'])->name('webhooks.paystack');
Route::post('/webhooks/flutterwave', [DonationPaymentController::class, 'flutterwaveWebhook'])->name('webhooks.flutterwave');

Route::get('/Charitize/api/donations.php', [DonationController::class, 'index']);
Route::post('/Charitize/api/donations.php', [DonationController::class, 'store'])
    ->middleware('throttle:10,1');

Route::post('/api/reports', [IncidentReportController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('reports.api.store');
Route::get('/api/reports/{incidentReport:reference}', [IncidentReportController::class, 'show'])
    ->where('incidentReport', 'SCVF-[0-9]{4}-[0-9]{6}')
    ->name('reports.api.show');

Route::post('/api/contacts', [ContactMessageController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contacts.api.store');

Route::post('/api/volunteers', [VolunteerApplicationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('volunteers.api.store');

Route::post('/api/newsletter', [NewsletterSubscriberController::class, 'store'])
    ->middleware('throttle:newsletter')
    ->name('newsletter.api.store');

Route::post('/api/testimonies', [TestimonyController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('testimonies.api.store');

Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{slug}', [NewsController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('news.show');

$legacyPages = [
    'about.html' => 'about',
    'services.html' => 'services',
    'service.html' => 'services',
    'donations.html' => 'donations',
    'donation.html' => 'donate',
    'news.html' => 'news',
    'event.html' => 'events',
    'team.html' => 'volunteer',
    'testimonial.html' => 'our-stand',
    'report-live.html' => 'report',
    'feature.html' => 'mission',
    'contact.html' => 'contact',
    '404.html' => 'help',
];

foreach ($legacyPages as $file => $name) {
    Route::get('/Charitize/'.$file, fn () => redirect()->route($name, status: 301));
}

Route::get('/Charitize/news/{slug}.html', function (string $slug) {
    return redirect()->route('news.show', $slug, 301);
})->where('slug', '[a-z0-9-]+');

Route::middleware('guest')->group(function () {
    Route::get('portal/login', [AuthenticatedSessionController::class, 'create'])->name('portal.login');
    Route::post('portal/login', [AuthenticatedSessionController::class, 'store'])->name('portal.login.store');
});

Route::middleware(['auth', EnsurePortalAccountIsActive::class, ExpireIdlePortalSession::class, EnsurePortalPermission::class])->group(function () {
    Route::get('portal', DashboardController::class)->name('portal.dashboard');
    Route::put('portal/donation-goal', [DonationGoalController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('portal.donation-goal.update');
    Route::get('portal/donors', [DonorController::class, 'index'])->name('portal.donors.index');
    Route::get('portal/donors/create', [DonorController::class, 'create'])->name('portal.donors.create');
    Route::post('portal/donors', [DonorController::class, 'store'])->name('portal.donors.store');
    Route::get('portal/donors/{donation}', [DonorController::class, 'show'])->name('portal.donors.show');
    Route::delete('portal/donors/{donation}', [DonorController::class, 'destroy'])->name('portal.donors.destroy');
    Route::get('portal/cases', [CaseController::class, 'index'])->name('portal.cases.index');
    Route::get('portal/cases/create', [CaseController::class, 'create'])->name('portal.cases.create');
    Route::post('portal/cases', [CaseController::class, 'store'])->name('portal.cases.store');
    Route::get('portal/cases/{incidentReport}/files/{incidentReportFile}', [CaseController::class, 'download'])
        ->name('portal.cases.files.download');
    Route::get('portal/cases/{incidentReport}', [CaseController::class, 'show'])->name('portal.cases.show');
    Route::put('portal/cases/{incidentReport}', [CaseController::class, 'update'])->name('portal.cases.update');
    Route::delete('portal/cases/{incidentReport}', [CaseController::class, 'destroy'])->name('portal.cases.destroy');
    Route::get('portal/contacts', [ContactController::class, 'index'])->name('portal.contacts.index');
    Route::get('portal/contacts/create', [ContactController::class, 'create'])->name('portal.contacts.create');
    Route::post('portal/contacts', [ContactController::class, 'store'])->name('portal.contacts.store');
    Route::get('portal/contacts/{contactMessage}', [ContactController::class, 'show'])->name('portal.contacts.show');
    Route::put('portal/contacts/{contactMessage}', [ContactController::class, 'update'])->name('portal.contacts.update');
    Route::delete('portal/contacts/{contactMessage}', [ContactController::class, 'destroy'])->name('portal.contacts.destroy');
    Route::get('portal/members/attendance', [MeetingAttendanceController::class, 'index'])->name('portal.members.attendance');
    Route::post('portal/members/attendance', [MeetingAttendanceController::class, 'store'])->name('portal.members.attendance.store');
    Route::get('portal/members', [MemberController::class, 'index'])->name('portal.members.index');
    Route::get('portal/members/create', [MemberController::class, 'create'])->name('portal.members.create');
    Route::post('portal/members', [MemberController::class, 'store'])->name('portal.members.store');
    Route::get('portal/members/{foundationMember}/edit', [MemberController::class, 'edit'])->name('portal.members.edit');
    Route::get('portal/members/{foundationMember}', [MemberController::class, 'show'])->name('portal.members.show');
    Route::put('portal/members/{foundationMember}/suspension', [MemberController::class, 'suspend'])->name('portal.members.suspend');
    Route::put('portal/members/{foundationMember}', [MemberController::class, 'update'])->name('portal.members.update');
    Route::delete('portal/members/{foundationMember}', [MemberController::class, 'destroy'])->name('portal.members.destroy');
    Route::get('portal/team', [TeamMemberController::class, 'index'])->name('portal.team.index');
    Route::get('portal/team/create', [TeamMemberController::class, 'create'])->name('portal.team.create');
    Route::post('portal/team', [TeamMemberController::class, 'store'])->name('portal.team.store');
    Route::get('portal/team/{teamMember}/edit', [TeamMemberController::class, 'edit'])->name('portal.team.edit');
    Route::put('portal/team/{teamMember}', [TeamMemberController::class, 'update'])->name('portal.team.update');
    Route::get('portal/team/{teamMember}', [TeamMemberController::class, 'show'])->name('portal.team.show');
    Route::delete('portal/team/{teamMember}', [TeamMemberController::class, 'destroy'])->name('portal.team.destroy');
    Route::get('portal/gallery', [GalleryItemController::class, 'index'])->name('portal.gallery.index');
    Route::get('portal/gallery/create', [GalleryItemController::class, 'create'])->name('portal.gallery.create');
    Route::post('portal/gallery', [GalleryItemController::class, 'store'])->name('portal.gallery.store');
    Route::get('portal/gallery/{galleryItem}/edit', [GalleryItemController::class, 'edit'])->name('portal.gallery.edit');
    Route::get('portal/gallery/{galleryItem}', [GalleryItemController::class, 'show'])->name('portal.gallery.show');
    Route::put('portal/gallery/{galleryItem}', [GalleryItemController::class, 'update'])->name('portal.gallery.update');
    Route::delete('portal/gallery/{galleryItem}', [GalleryItemController::class, 'destroy'])->name('portal.gallery.destroy');
    Route::get('portal/testimonies', [PortalTestimonyController::class, 'index'])->name('portal.testimonies.index');
    Route::get('portal/testimonies/create', [PortalTestimonyController::class, 'create'])->name('portal.testimonies.create');
    Route::post('portal/testimonies', [PortalTestimonyController::class, 'store'])->name('portal.testimonies.store');
    Route::get('portal/testimonies/{testimony}', [PortalTestimonyController::class, 'show'])->name('portal.testimonies.show');
    Route::put('portal/testimonies/{testimony}', [PortalTestimonyController::class, 'update'])->name('portal.testimonies.update');
    Route::delete('portal/testimonies/{testimony}', [PortalTestimonyController::class, 'destroy'])->name('portal.testimonies.destroy');
    Route::get('portal/events', [PortalEventController::class, 'index'])->name('portal.events.index');
    Route::get('portal/events/create', [PortalEventController::class, 'create'])->name('portal.events.create');
    Route::post('portal/events', [PortalEventController::class, 'store'])->name('portal.events.store');
    Route::get('portal/events/{foundationEvent}/edit', [PortalEventController::class, 'edit'])->name('portal.events.edit');
    Route::get('portal/events/{foundationEvent}', [PortalEventController::class, 'show'])->name('portal.events.show');
    Route::put('portal/events/{foundationEvent}', [PortalEventController::class, 'update'])->name('portal.events.update');
    Route::delete('portal/events/{foundationEvent}', [PortalEventController::class, 'destroy'])->name('portal.events.destroy');
    Route::get('portal/news', [PortalNewsController::class, 'index'])->name('portal.news.index');
    Route::get('portal/news/create', [PortalNewsController::class, 'create'])->name('portal.news.create');
    Route::post('portal/news', [PortalNewsController::class, 'store'])->name('portal.news.store');
    Route::get('portal/news/{newsArticle}/edit', [PortalNewsController::class, 'edit'])->name('portal.news.edit');
    Route::get('portal/news/{newsArticle}', [PortalNewsController::class, 'show'])->name('portal.news.show');
    Route::put('portal/news/{newsArticle}', [PortalNewsController::class, 'update'])->name('portal.news.update');
    Route::delete('portal/news/{newsArticle}', [PortalNewsController::class, 'destroy'])->name('portal.news.destroy');
    Route::get('portal/volunteers', [VolunteerController::class, 'index'])->name('portal.volunteers.index');
    Route::get('portal/volunteers/create', [VolunteerController::class, 'create'])->name('portal.volunteers.create');
    Route::post('portal/volunteers', [VolunteerController::class, 'store'])->name('portal.volunteers.store');
    Route::get('portal/volunteers/{volunteerApplication}', [VolunteerController::class, 'show'])->name('portal.volunteers.show');
    Route::put('portal/volunteers/{volunteerApplication}', [VolunteerController::class, 'update'])->name('portal.volunteers.update');
    Route::delete('portal/volunteers/{volunteerApplication}', [VolunteerController::class, 'destroy'])->name('portal.volunteers.destroy');
    Route::get('portal/subscribers', [PortalNewsletterSubscriberController::class, 'index'])->name('portal.subscribers.index');
    Route::get('portal/subscribers/create', [PortalNewsletterSubscriberController::class, 'create'])->name('portal.subscribers.create');
    Route::post('portal/subscribers', [PortalNewsletterSubscriberController::class, 'store'])->name('portal.subscribers.store');
    Route::get('portal/subscribers/{newsletterSubscriber}', [PortalNewsletterSubscriberController::class, 'show'])->name('portal.subscribers.show');
    Route::put('portal/subscribers/{newsletterSubscriber}', [PortalNewsletterSubscriberController::class, 'update'])->name('portal.subscribers.update');
    Route::delete('portal/subscribers/{newsletterSubscriber}', [PortalNewsletterSubscriberController::class, 'destroy'])->name('portal.subscribers.destroy');
    Route::get('portal/notifications', [NotificationController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('portal.notifications.index');
    Route::post('portal/notifications/read-all', [NotificationController::class, 'destroy'])
        ->middleware('throttle:30,1')
        ->name('portal.notifications.read-all');
    Route::post('portal/notifications/{notification}/read', [NotificationController::class, 'update'])
        ->middleware('throttle:30,1')
        ->name('portal.notifications.read');
    Route::get('portal/admins', [AdminUserController::class, 'index'])->name('portal.admins.index');
    Route::post('portal/admins', [AdminUserController::class, 'store'])->name('portal.admins.store');
    Route::put('portal/admins/{user}/role', [AdminUserController::class, 'assignRole'])->name('portal.admins.role');
    Route::put('portal/admins/{user}', [AdminUserController::class, 'update'])->name('portal.admins.update');
    Route::delete('portal/admins/{user}', [AdminUserController::class, 'destroy'])->name('portal.admins.destroy');
    Route::get('portal/roles', [AdminRoleController::class, 'index'])->name('portal.roles.index');
    Route::post('portal/roles', [AdminRoleController::class, 'store'])->name('portal.roles.store');
    Route::get('portal/roles/{adminRole}/edit', [AdminRoleController::class, 'edit'])->name('portal.roles.edit');
    Route::put('portal/roles/{adminRole}', [AdminRoleController::class, 'update'])->name('portal.roles.update');
    Route::delete('portal/roles/{adminRole}', [AdminRoleController::class, 'destroy'])->name('portal.roles.destroy');
    Route::get('portal/audit', [AuditReportController::class, 'index'])->name('portal.audit.index');
    Route::post('portal/audit', [AuditReportController::class, 'store'])->name('portal.audit.store');
    Route::get('portal/mail', [MailCenterController::class, 'index'])->name('portal.mail.index');
    Route::post('portal/mail', [MailCenterController::class, 'store'])->name('portal.mail.store');
    Route::get('portal/login-bans', [LoginBanController::class, 'index'])->name('portal.login-bans.index');
    Route::delete('portal/login-bans/{portalLoginBan}', [LoginBanController::class, 'destroy'])->name('portal.login-bans.destroy');
    Route::get('portal/site', [SiteProfileController::class, 'edit'])->name('portal.site.edit');
    Route::put('portal/site', [SiteProfileController::class, 'update'])->name('portal.site.update');
    Route::put('portal/site/gallery', [SiteProfileController::class, 'updateGallery'])->name('portal.site.gallery');
    Route::get('portal/settings', [SettingsController::class, 'edit'])->name('portal.settings.edit');
    Route::put('portal/settings/phone', [SettingsController::class, 'updatePhone'])->name('portal.settings.phone');
    Route::put('portal/settings', [SettingsController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('portal.settings.update');
    Route::post('portal/logout', [AuthenticatedSessionController::class, 'destroy'])->name('portal.logout');
});
