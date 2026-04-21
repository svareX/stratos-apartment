<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class LocaleController extends BaseController
{
    /**
     * Invokable controller: switch the application locale and redirect the user
     * to the same path under the requested locale prefix.
     */
    public function __invoke(Request $request, string $locale)
    {
        $available = ['cs', 'en', 'de'];

        if (! in_array($locale, $available, true)) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        $previous = url()->previous();
        $path = parse_url($previous, PHP_URL_PATH) ?: '/';
        $segments = explode('/', trim($path, '/'));

        if (isset($segments[0]) && in_array($segments[0], $available, true)) {
            $segments[0] = $locale;
            $newPath = '/'.implode('/', $segments);
        } else {
            $newPath = '/'.$locale.($path === '/' ? '' : $path);
        }

        return redirect($newPath);
    }
}
