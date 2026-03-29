<?php

namespace App\Support;

use App\Models\User;

class LegacyAuthSessionBridge
{
    public function login(User $user): void
    {
        // Close Laravel's session first (it uses a custom FileSessionHandler that
        // writes to laravel-auth/storage/framework/sessions/, not PHP's native
        // session path). We must reset to the native handler so Simple Invoices
        // can read the session from the same location it writes to.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        session_set_save_handler(new \SessionHandler());
        session_name('PHPSESSID');
        session_start();
        session_regenerate_id(true);

        $_SESSION['SI_Auth'] = [
            'id'        => (string) $user->getAuthIdentifier(),
            'email'     => (string) $user->email,
            'role_name' => (string) ($user->role_name ?? ''),
            'domain_id' => (string) $user->domain_id,
            'user_id'   => (string) $user->user_id,
        ];

        unset($_SESSION['SI_Auth']['fake_auth']);

        session_write_close();
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        session_set_save_handler(new \SessionHandler());
        session_name('PHPSESSID');
        session_start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
        }

        session_destroy();
    }

    public function targetPath(User $user): string
    {
        if (($user->role_name ?? null) === 'customer' && (int) $user->user_id > 0) {
            return '/index.php?module=customers&view=details&action=view&id='.(int) $user->user_id;
        }

        return '/';
    }

}
