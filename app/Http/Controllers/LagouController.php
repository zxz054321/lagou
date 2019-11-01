<?php

namespace App\Http\Controllers;

use App\LagouPosition;
use Illuminate\Http\Request;

class LagouController extends Controller
{
    public function pour(Request $request)
    {
        foreach ($request->input('content.positionResult.result') as $position) {
            $position['updateTime'] = now();

            LagouPosition::updateOrCreate(['positionId' => $position['positionId']], (array) $position);
        }
    }
}
