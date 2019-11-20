<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    /**
     * 本模型不应在命令行以外的地方做写操作
     */
    protected $guarded = [];

    /**
     * 用于数据清洗时录入原始文本
     */
    public function setSizeAttribute(string $value): void
    {
        if (Str::contains($value, '少于')) {
            preg_match('/.*(\d+).*/', $value, $matches);
            $this->attributes['size_to'] = $matches[1];
            return;
        }

        if (Str::contains($value, '以上')) {
            $this->attributes['size_from'] = str_replace('人以上', '', $value);
            return;
        }

        $value = str_replace('人', '', $value);
        list($this->attributes['size_to'], $this->attributes['size_from']) = explode('-', $value);
    }
}
