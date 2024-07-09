<?php

use App\Notifications\ActivationRequestNotification;
use App\Notifications\NewFileNotification;
use App\Notifications\ReminderNotification;
use App\Notifications\ResendRequestNotification;
use App\Notifications\SendCodeNotification;
use App\Notifications\SendRejectNotification;
use App\Notifications\VerificationNotification;
use App\Notifications\WelcomeNotification;
use App\Models\User;

if (!function_exists("sendWOWNote")) {
    function sendWOWNote($userID)
    {
        $recipients = User::activeSuperAdminCanReceiveEmails()->get();
        if ($recipients->isNotEmpty()) {
            foreach ($recipients as $recipient) {
                if ($recipient->can_receive_reg_emails) {
                    $notification = new ActivationRequestNotification($userID);
                    \Notification::send($recipient, $notification);
                    $notification->log($recipient);
                }
            }
        }
    }
}

if (!function_exists('sendWOWNewFile')) {
    function sendWOWNewFile()
    {
        $recipients = User::activeSuperAdminCanReceiveEmails()->get();

        if ($recipients->isNotEmpty()) {
            $notification = new NewFileNotification();
            \Notification::send($recipients, $notification);
            $notification->log($recipients);
        }
    }
}

if (!function_exists("sendWowResendCode")) {
    function sendWowResendCode($requestID, $message)
    {
        $recipients = User::activeSuperAdminCanReceiveEmails()->get();
        if ($recipients->isNotEmpty()) {
            $notification = new ResendRequestNotification($requestID, $message);
            \Notification::send($recipients, $notification);
            $notification->log($recipients);
        }
    }
}

if (!function_exists("sendWelcomeNotification")) {
    function sendWelcomeNotification($userID)
    {
        $recipient = User::activeCanReceiveEmails()->find($userID);
        if ($recipient) {
            logger("send welcome notification ");
            $notification = new WelcomeNotification();
            \Notification::send($recipient, $notification);
            $notification->log($recipient);
        }
    }
}

if (!function_exists("sendVerificationNotification")) {
    function sendVerificationNotification($userID)
    {
        $recipient = User::whereCanReceiveEmails(true)->find($userID);

        if ($recipient) {
            $notification = new VerificationNotification($userID);
            \Notification::send($recipient, $notification);
            $notification->log($recipient);
        }
    }
}

if (!function_exists("sendReminderNotification")) {
    function sendReminderNotification($count)
    {
        $recipients = User::activeSuperAdminCanReceiveEmails()->get();
        if ($recipients->isNotEmpty()) {
            $notification = new ReminderNotification($count);
            \Notification::send($recipients, $notification);
            $notification->log($recipients);
        }
    }
}

if (!function_exists("sendCustomerCodeNotification")) {
    function sendCustomerCodeNotification($customer, $code)
    {
        if ($customer instanceof User && $customer->isActiveCanReceiveEmails()) {
            $notification = new SendCodeNotification($code, $customer);
            \Notification::send($customer, $notification);
            $notification->log($customer);
        }
    }
}

if (!function_exists("sendCustomerRejectNotification")) {
    function sendCustomerRejectNotification($customer)
    {
        if ($customer instanceof User && $customer->isActiveCanReceiveEmails()) {
            $notification = new SendRejectNotification($customer);
            \Notification::send($customer, $notification);
            $notification->log($customer);
        }
    }
}

