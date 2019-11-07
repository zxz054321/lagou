<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LagouPosition extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'positionId';

    protected $fillable = [
        'positionId',
        'positionCategory',
        'positionName',
        'companyId',
        'companyFullName',
        'companyShortName',
        'companySize',
        'industryField',
        'financeStage',
        'firstType',
        'secondType',
        'thirdType',
        'skillLables',
        'positionLables',
        'industryLables',
        'city',
        'district',
        'businessZones',
        'salary',
        'workYear',
        'jobNature',
        'education',
        'positionAdvantage',
        'publisherId',
        'subwayline',
        'stationname',
        'linestaion',
        'latitude',
        'longitude',
        'createTime',
        'updateTime',
    ];

    protected $casts = [
        'skillLables' => 'array',
        'positionLables' => 'array',
        'industryLables' => 'array',
        'businessZones' => 'array',
    ];
}
