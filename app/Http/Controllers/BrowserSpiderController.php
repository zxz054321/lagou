<?php

namespace App\Http\Controllers;

class BrowserSpiderController extends Controller
{
    public function jobPages()
    {
        return collect(['PHP', 'Java', 'Go', 'python', 'Ruby'])->map(function (string $job) {
            return "https://www.lagou.com/jobs/list_{$job}?px=new&city=%E5%B9%BF%E5%B7%9E";
        });
    }
}
