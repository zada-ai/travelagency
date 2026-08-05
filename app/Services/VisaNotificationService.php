<?php

namespace App\Services;

use App\Models\VisaApplication;
use Illuminate\Support\Facades\Log;

class VisaNotificationService
{
    /**
     * Send email and whatsapp notifications on status updates.
     */
    public static function sendStatusNotification(VisaApplication $application)
    {
        $status = $application->status;
        $customer = $application->customer_name;
        $passport = $application->passport_number;

        $subject = "Visa Application Update: " . $status;
        $message = "Dear Customer, your Visa Application (ID: #{$application->id}, Passport: {$passport}) status has been updated to '{$status}'.";

        if ($status === 'Submitted') {
            $message = "Hello {$customer}, your Visa Application (ID: #{$application->id}, Passport: {$passport}) has been successfully submitted for processing.";
        } elseif ($status === 'Documents Required') {
            $message = "Action Required: Hello {$customer}, additional or corrected documents are required for your Visa Application #{$application->id}. Please upload them immediately.";
        } elseif ($status === 'Approved') {
            $message = "Congratulations {$customer}! Your Visa Application #{$application->id} has been Approved.";
        } elseif ($status === 'Rejected') {
            $message = "Notice: Hello {$customer}, we regret to inform you that your Visa Application #{$application->id} has been Rejected. Remarks: {$application->remarks}";
        } elseif ($status === 'Issued') {
            $message = "Congratulations {$customer}! Your Visa has been successfully Issued. You can download your copy from the portal.";
        }

        // Log simulation entries to visa_notifications.log
        $logEntry = "[" . now()->toDateTimeString() . "] [VISA NOTIFICATION] [Application ID: #{$application->id}] STATUS: {$status} | CUSTOMER: {$customer} | SUBJECT: {$subject} | MESSAGE: {$message}\n";
        
        $logPath = storage_path('logs/visa_notifications.log');
        
        // Ensure logs directory exists
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }
        
        file_put_contents($logPath, $logEntry, FILE_APPEND);

        // Also output standard Laravel Log
        Log::info("Visa Application Status Update dispatched for ID #{$application->id} - New Status: {$status}");
    }
}
