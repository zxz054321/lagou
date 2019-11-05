<?php

namespace App\Http\Controllers;

use App\LagouPosition;

class LagouController extends Controller
{
    public function pour()
    {
        $data = json_decode(file_get_contents('php://input'));

        foreach ($data->content->positionResult->result as $position) {
            $position->updateTime = now();

            LagouPosition::updateOrCreate(['positionId' => $position->positionId], (array) $position);
        }
    }
}
