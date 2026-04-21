<?php

namespace App\Filament\Resources\Apartments\Pages;

use App\Filament\Resources\Apartments\ApartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApartment extends CreateRecord
{
    protected static string $resource = ApartmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['tags']) && is_array($data['tags'])) {
            $tags_en = [];
            $tags_cs = [];
            $tags_de = [];

            foreach ($data['tags'] as $tag) {
                $en = trim($tag['value_en'] ?? '');
                $cs = trim($tag['value_cs'] ?? '');
                $de = trim($tag['value_de'] ?? '');

                if ($en !== '') {
                    $tags_en[] = ['value' => $en];
                }
                if ($cs !== '') {
                    $tags_cs[] = ['value' => $cs];
                }
                if ($de !== '') {
                    $tags_de[] = ['value' => $de];
                }
            }

            $data['tags_en'] = $tags_en;
            $data['tags_cs'] = $tags_cs;
            $data['tags_de'] = $tags_de;

            // keep legacy tags column in sync with English tags
            $data['tags'] = $tags_en;
        }

        return $data;
    }
}
