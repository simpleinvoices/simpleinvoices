<?php

namespace App\Support;

use App\Models\User;

class LegacyAuthSessionBridge
{
    public function login(User $user): void
    {
        $this->startNativeSession();

        session_regenerate_id(true);

        $_SESSION['SI_Auth'] = [
            'id' => (string) $user->getAuthIdentifier(),
            'email' => (string) $user->email,
            'role_name' => (string) ($user->role_name ?? ''),
            'domain_id' => (string) $user->domain_id,
            'user_id' => (string) $user->user_id,
        ];

        unset($_SESSION['SI_Auth']['fake_auth']);

        session_write_close();
    }

    public function logout(): void
    {
        $this->startNativeSession();

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

    protected function startNativeSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name('PHPSESSID');
        session_start();
    }
}
