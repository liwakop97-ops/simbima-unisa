<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (HttpException $exception, Request $request) {
            if (
                $exception->getStatusCode() !== 419
                || ! ($exception->getPrevious() instanceof TokenMismatchException)
                || ! $request->is('logout')
            ) {
                return null;
            }

            if (Auth::check()) {
                return redirect()->route('dashboard')
                    ->with('success', 'Sesi halaman telah diperbarui. Silakan klik Keluar kembali.');
            }

            return redirect()->route('login')
                ->with('success', 'Sesi Anda telah berakhir. Silakan masuk kembali.');
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
