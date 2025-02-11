<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Request;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';

        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'name' => ucfirst(str_replace('-', ' ', $segment)),
                'url' => url($url)
            ];
        }

        $view->with('breadcrumbs', $breadcrumbs);
    }
}