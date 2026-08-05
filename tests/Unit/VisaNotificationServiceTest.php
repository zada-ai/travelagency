<?php

use App\Models\VisaApplication;
use App\Services\VisaNotificationService;

it('does not create a custom notification log file for each submission', function () {
    $logPath = storage_path('logs/visa_notifications.log');

    if (file_exists($logPath)) {
        unlink($logPath);
    }

    $application = new VisaApplication([
        'id' => 999,
        'customer_name' => 'Test Customer',
        'passport_number' => 'TEST123',
        'status' => 'Submitted',
        'remarks' => 'Test remarks',
    ]);

    VisaNotificationService::sendStatusNotification($application);

    expect(file_exists($logPath))->toBeFalse();
});
