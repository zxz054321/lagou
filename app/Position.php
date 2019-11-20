<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Position extends Model
{
    /**
     * 本模型不应在命令行以外的地方做写操作
     */
    protected $guarded = [];

    public function getCompanyFullNameAttribute(): string
    {
        return $this->company()->value('full_name');
    }

    public function getCompanyShortNameAttribute(): string
    {
        return $this->company()->value('short_name');
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setSalaryAttribute(string $value): void
    {
        $range = str_replace('k', '', strtolower($value));

        list($this->attributes['salary_from'], $this->attributes['salary_to']) = explode('-', $range);
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setWorkSeniorityAttribute(string $value): void
    {
        if (Str::contains($value, '以下')) {
            preg_match('/.*(\d+).*/', $value, $matches);
            $this->attributes['work_seniority_to'] = $matches[1];
            return;
        }

        if (Str::contains($value, '以上')) {
            preg_match('/.*(\d+).*/', $value, $matches);
            $this->attributes['work_seniority_from'] = $matches[1];
            return;
        }

        if ($value == '应届毕业生') {
            $this->attributes['work_seniority_from'] = 0;
            $this->attributes['work_seniority_to']   = 0;
            return;
        }

        if ($value == '不限') {
            $this->attributes['work_seniority_from'] = null;
            $this->attributes['work_seniority_to']   = null;
            return;
        }

        $range = str_replace('年', '', $value);

        list($this->attributes['work_seniority_from'], $this->attributes['work_seniority_to']) = explode('-', $range);
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setIndustryLabelsAttribute(string $value): void
    {
        $this->attributes['industry_labels'] = implode(',', json_decode($value, true));
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setPositionLabelsAttribute(string $value): void
    {
        $this->attributes['position_labels'] = implode(',', json_decode($value, true));
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setSkillLabelsAttribute(string $value): void
    {
        $this->attributes['skill_labels'] = implode(',', json_decode($value, true));
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setDistrictAttribute(?string $value): void
    {
        $this->attributes['district'] = $value ?: '';
    }

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setBusinessZonesAttribute(?string $value): void
    {
        $this->attributes['business_zones'] = $value ? implode(',', json_decode($value, true)) : '';
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeStrategy(Builder $query, string $strategy): Builder
    {
        switch ($strategy) {
            case '不加班':
                return $query
                    ->where('advantage', 'like', '%不%加班%')
                    ->orWhere('advantage', 'like', '%少%加班%')
                    ->orWhere('advantage', 'like', '%加班少%')
                    ->orWhere('advantage', 'like', '%加班%少%');

            default:
                exit('无此策略');
        }
    }
}
