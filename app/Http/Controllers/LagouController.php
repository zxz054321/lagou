<?php

namespace App\Http\Controllers;

use App\LagouPosition;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LagouController extends Controller
{
    public function pour(Request $request)
    {
        $data = json_decode(file_get_contents('php://input'));

        if (!$data) {
            return;
        }

        preg_match('/list_(\w+)\?/', $request->header('referer'), $matches);

        foreach ($data->content->positionResult->result as $position) {
            $position->positionCategory = strtolower($matches[1]);
            $position->updateTime       = now();

            try {

                LagouPosition::updateOrCreate(['positionId' => $position->positionId], (array) $position);

            } catch (QueryException $e) {
                if (Str::contains($e->getMessage(), 'Duplicate entry')) {
                    return;
                }

                throw $e;
            }
        }
    }
}
