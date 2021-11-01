<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        if (isset($data['languages'])) {
            for ($i = 0; $i < count($data['languages']); $i++) {
                $data['languages'][$i]['image_id'] = $data['languages'][$i]['pivot']['image_id'];
                $data['languages'][$i]['translation'] = $data['languages'][$i]['pivot']['translation'];
                $data['languages'][$i]['transcription'] = $data['languages'][$i]['pivot']['transcription'];
                unset($data['languages'][$i]['pivot']);
            }
        }

        return $data;
//        return parent::toArray($request);
    }
}
