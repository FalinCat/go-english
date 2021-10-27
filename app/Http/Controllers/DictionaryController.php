<?php

namespace App\Http\Controllers;

use App\Models\Word;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DictionaryController extends Controller
{
    public function getAllWords()
    {
        return Word::all();
    }

    public function getWord($id)
    {
        $word = Word::query()->find($id);


        if (!$word) {
            $error = ['error' => 'No such ID'];
            return response()->json($error, 404);
        }

        return response()->json($word);
    }

    public function addWord(Request $request): JsonResponse
    {
//        $validatedWord = $request->validate([
//            'russian_translation' => 'required|string|max:255',
//            'english_translation' => 'required|string|max:255',
//            'transcription' => 'required|string|max:255|nullable'
//        ]);

        $rules = [
            'russian_translation' => 'required|string|max:255',
            'english_translation' => 'required|string|max:255',
            'transcription' => 'required|string|max:255|nullable'
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['status' => $validator->errors()]);
        }

        $validData = $validator->validate();

        $word = Word::query()->create([
            'russian_translation' => $validData['russian_translation'],
            'english_translation' => $validData['english_translation'],
            'transcription' => $validData['transcription'],
        ]);


        return response()->json([
            'status' => 'Word added with ID ' . $word->attributesToArray()['id']
        ]);
    }
}
