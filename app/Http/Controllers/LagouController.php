<?php

namespace App\Http\Controllers;

use App\LagouPosition;
use Illuminate\Http\Request;

class LagouController extends Controller
{
    public function pour(Request $request)
    {
        $data = json_decode(file_get_contents('php://input'));

        foreach ($data->content->positionResult->result as $position) {
            $position->updateTime = now();

            LagouPosition::updateOrCreate(['positionId' => $position->positionId], (array) $position);
        }
    }
}
