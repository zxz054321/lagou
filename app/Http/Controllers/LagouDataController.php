<?php

namespace App\Http\Controllers;

use App\LagouPosition;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LagouDataController extends Controller
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
            $position->updateTime = now();

            try {

                LagouPosition::updateOrCreate(['positionId' => $position->positionId], (array) $position);

            } catch (QueryException $exception) {
                // 忽略同一瞬间同时插入报错
                if (Str::contains($exception->getMessage(), 'Duplicate entry')) {
                    return;
                }

                throw $exception;
            }
        }
    }
}
