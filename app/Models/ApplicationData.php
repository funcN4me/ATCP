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
    private const TOKEN = 'B4NKGg=fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l';

//    public const BASE_URI = "https://api.apptica.com/package/top_history/1421444/1?date_from=2023-12-23&date_to=2023-12-25&B4NKGg=fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l";

    public static function getUri(string $date, $appId = self::APP_ID, $countryId = self::COUNTRY_ID): string
    {
        $dateFrom = 'date_from=' . $date;
        $dateTo = 'date_to=' . $date;

        return self::BASE_URI . self::APP_ID . '/' . self::COUNTRY_ID . '?' . implode('&', [$dateFrom, $dateTo, self::TOKEN]);
    }
}
