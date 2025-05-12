<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->segment(1); // Ambil prefix {lang} dari URL

        // if (!in_array($lang, ['en', 'id'])) {
        //     $lang = Session::get('locale', 'id');
        //     // Redirect ke halaman yang sama dengan prefix bahasa yang benar
        //     return redirect($lang . '/' . ltrim($request->getRequestUri(), '/'));
        // }
        // Jika segment pertama bukan 'en' atau 'id', abaikan middleware ini
        if (!in_array($lang, ['en', 'id'])) {
            return $next($request);
        }

        App::setLocale($lang);
        Session::put('locale', $lang);

        return $next($request);
    }
}
