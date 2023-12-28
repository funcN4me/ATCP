<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationData extends Model
{
    use HasFactory;

    public $fillable = [
        'date',
        'category_id',
        'position',
    ];
    private const APP_ID = 1421444;
    private const COUNTRY_ID = 1;
    private const BASE_URI = 'https://api.apptica.com/package/top_history/';

    public static function getUri(string $date, $appId = self::APP_ID, $countryId = self::COUNTRY_ID): string
    {
        $dateFrom = 'date_from=' . $date;
        $dateTo = 'date_to=' . $date;

        return self::BASE_URI . $appId . '/' . $countryId . '?' . implode('&', [$dateFrom, $dateTo, env('TOKEN')]);
    }
}
