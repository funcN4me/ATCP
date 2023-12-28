<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetAppTopCategoryRequest;
use App\Models\ApplicationData;
use Carbon\Carbon;
use Error;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

class ApplicationTopCategory extends Controller
{
    /**
     * @param getAppTopCategoryRequest $request
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function getAppTopCategory(GetAppTopCategoryRequest $request): JsonResponse
    {
        $request = $request->input();
        $onDate = $request['date'];
        $formattedDate = Carbon::createFromFormat('Y-m-d', $onDate);
        $currentDate = Carbon::createFromFormat('Y-m-d', now()->format('Y-m-d'));

        $applicationData = $this->getDataFromDB($onDate);


        if ($applicationData && $formattedDate->lt($currentDate)) {
            return response()->json(['status_code' => 200, 'message' => 'ok', 'data' => $applicationData]);
        }

        $applicationData = $this->getDataFromApi($onDate);
        $dataToInsert = [];
        $dataToReturn = [];

        foreach ($applicationData['data'] as $categoryId => $innerArray) {
            $minPosition = min(array_column($innerArray, $onDate));
            $dataToInsert[] = [
                'date' => $onDate,
                'category_id' => $categoryId,
                'position' => $minPosition,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $dataToReturn[$categoryId] = $minPosition;
        }

        foreach ($dataToInsert as $data) {
            ApplicationData::updateOrCreate([
                'date' => $data['date'],
                'category_id' => $data['category_id'],
            ], [
                'position' => $data['position'],
            ]);
        }

//      Массовый upsert не работает, к сожалению, без модификаторов unique в БД
//      ApplicationData::upsert($dataToInsert, ['date', 'category_id'], ['position']);

        return response()->json(['status_code' => 200, 'message' => 'ok', 'data' => $dataToReturn]);
    }

    /**
     * @param string $date
     * @return ApplicationData|null
     */

    protected function getDataFromDB(string $date): array|null
    {
        return ApplicationData::select('category_id', 'position')->where('date', $date)->get()->pluck('position', 'category_id')->toArray();
    }

    /**
     * @param string $date
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    protected function getDataFromApi(string $date): array|null
    {
        $client = new Client();

        $data = $client->request('GET', ApplicationData::getUri($date));

        $response = json_decode($data->getBody()->getContents(), true);

        if ((int)$response['status_code'] === 200) {
            return $response;
        }

        throw new Error('Что-то пошло не так', 500);
    }
}
