<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordResource;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $word = WordResource::collection(Word::with('languages')->get());

        return response()->json($word);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'translation' => 'required|string|max:255',
            "transcription" => 'required|string|max:255',
            "languages.*.id" => 'required|string|max:255|exists:languages,id',
            "languages.*.image_id" => 'numeric|nullable|exists:images,id',
            "languages.*.translation" => 'required|string|max:255',
            "languages.*.transcription" => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $word = Word::query()->create([
                'translation' => $request['translation'],
                'transcription' => $request['transcription'],
            ]);
            foreach ($request['languages'] as $language) {
                $word->languages()->attach($language['id'],
                    [
                        'translation' => $language['translation'],
                        'transcription' => $language['transcription'],
                        'image_id' => $language['image_id']
                    ]);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        return response()->json($this->findWordWithIdOrName($word['id']));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Word $word
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $word = $this->findWordWithIdOrName($id);
        return response()->json($word);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Word $word
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'translation' => 'sometimes|string|max:255',
            "transcription" => 'sometimes|string|max:255',
            "languages.*.id" => 'required|string|max:255|exists:languages,id',
            "languages.*.image_id" => 'sometimes|numeric|nullable|exists:images,id',
            "languages.*.translation" => 'sometimes|string|max:255',
            "languages.*.transcription" => 'sometimes|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }


        $word = Word::query()->find($id);

        // Обновляем основное слово, если требуется
        if ($request['translation']) {
            $wordData['translation'] = $request['translation'];
        }
        if ($request['transcription']) {
            $wordData['transcription'] = $request['transcription'];
        }
        if (isset($wordData)) {
            $word->update($wordData);
        }

        // Обновляем перевод
        if (isset($request['languages'])) {

            foreach ($request['languages'] as $language) {
                if (isset($language['image_id'])) {
                    $langData['image_id'] = $language['image_id'];
                }
                if (isset($language['translation'])) {
                    $langData['translation'] = $language['translation'];
                }
                if (isset($language['transcription'])) {
                    $langData['transcription'] = $language['transcription'];
                }

                $word->languages()->updateExistingPivot(
                    $language['id'], $langData
                );
            }
        }


        $word = $this->findWordWithIdOrName($id);
        return response()->json($word);



//
//        $word->languages()->update(
//            $request->toArray()
//        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Word $word
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::query()->find($id);
        if (is_null($word)) {
            return response()->json(['message' => 'Cannot find word by id: ' . $id], 404);
        }
        $word->delete();
        return response(null, 204);
    }

    /**
     * @param $value
     * @param bool $asCollection
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function findWordWithIdOrName($value): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $operator = '=';
        $column = 'id';

        if (!is_numeric($value)) {
            $operator = 'LIKE';
            $column = 'translation';
            $value = '%' . $value . '%';
        }
        if (isset($_GET['word'])) {
            $operator = 'LIKE';
            $column = 'translation';
            $value = '%' . $_GET['word'] . '%';
        }

            $data = WordResource::collection(Word::with('languages')->where($column, $operator, $value)->get());


        return $data;

    }
}
