<?php

namespace App\Http\Controllers;

class UiBotController extends Controller
{
    public function jobUrls()
    {
        return collect(['PHP', 'Java', 'Go', 'python', 'Ruby'])->map(function (string $job) {
            return "https://www.lagou.com/jobs/list_{$job}?px=new&city=%E5%B9%BF%E5%B7%9E";
        });
    }
}
