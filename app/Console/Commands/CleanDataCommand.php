<?php

namespace App\Console\Commands;

use App\Company;
use App\Position;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CleanDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'lagou:clean-data';

    /**
     * The console command description.
     */
    protected $description = '进行数据清洗';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('lagou_positions')->orderBy('positionId')->chunk(100, function (Collection $positions) {
            foreach ($positions as $position) {
                // dump(json_decode($position->businessZones));

                Company::updateOrCreate(['id' => $position->companyId], [
                    'full_name' => $position->companyFullName,
                    'short_name' => $position->companyShortName,
                    'finance_stage' => $position->financeStage,
                    'size' => $position->companySize,
                    'industry_tags' => $position->industryField,
                ]);

                Position::updateOrCreate(['id' => $position->positionId], [
                    'company_id' => $position->companyId,
                    'publisher_id' => $position->publisherId,

                    'category' => $position->positionCategory,
                    'first_type' => $position->firstType,
                    'second_type' => $position->secondType,
                    'third_type' => $position->thirdType,

                    'name' => $position->positionName,
                    'advantage' => $position->positionAdvantage,
                    'salary' => $position->salary,
                    'work_seniority' => $position->workYear,
                    'job_nature' => $position->jobNature,
                    'degree' => $position->education,

                    'industry_labels' => $position->industryLables,
                    'position_labels' => $position->positionLables,
                    'skill_labels' => $position->skillLables,

                    'city' => $position->city,
                    'district' => $position->district,
                    'business_zones' => $position->businessZones,
                    'subway_line' => $position->subwayline,
                    'subway_station' => $position->stationname,
                    'subway_linestation' => $position->linestaion,
                    'latitude' => $position->latitude,
                    'longitude' => $position->longitude,

                    'created_at' => $position->createTime,
                    'updated_at' => $position->updateTime,
                ]);
            }
        });
    }
}
