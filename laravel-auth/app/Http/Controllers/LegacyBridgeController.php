<?php

namespace App\Http\Controllers;

use App\Support\LegacyAuthSessionBridge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegacyBridgeController extends Controller
{
    public function __construct(
        protected LegacyAuthSessionBridge $bridge
    ) {
    }

    public function bridge(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if(! $user, 403);

        $this->bridge->login($user);

        return redirect()->to($this->bridge->targetPath($user));
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->bridge->logout();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
