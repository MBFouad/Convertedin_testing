<?php

namespace App\Notifications\Traits;

use App\Models\User;
use function file_exists;
use function resource_path;

trait MailViewFileResolverTrait
{
    /**
     * @param User $notifiable
     */
    private function resolveMailViewFile(string $templateKey, $notifiable): string
    {
        $userLang = !empty($notifiable->language->name) ? $notifiable->language->name : null;

        $langPath = $userLang ? resource_path('views/emails/' . $templateKey . '_' . $userLang . '.blade.php') : null;

        $viewFile = file_exists($langPath) && $userLang
            ? 'emails.' . $templateKey . '_' . $userLang
            : 'emails.' . $templateKey;

        return $viewFile;
    }
}
