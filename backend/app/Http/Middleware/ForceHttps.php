<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Redirect non-https requests to https in production and add HSTS header.
     */
    public function handle(Request $request, Closure $next)
    {
        // Chỉ áp dụng ở production (tránh redirect vòng lặp khi dev local).
        if (app()->environment('production') && !$request->secure()) {
            // Dựa vào host hiện tại + URI; đã trust proxies nên secure() phản ánh X-Forwarded-Proto.
            $secureUrl = 'https://' . $request->getHttpHost() . $request->getRequestUri();
            return redirect()->to($secureUrl, 301);
        }

        $response = $next($request);

        // Thêm HSTS header khi request đã là https để trình duyệt ghi nhớ (1 năm).
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
