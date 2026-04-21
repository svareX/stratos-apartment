<?php

namespace App\Filament\Resources\Apartments\Pages;

use App\Filament\Resources\Apartments\ApartmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApartment extends EditRecord
{
    protected static string $resource = ApartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // build unified tags array from tags_en/tags_cs/tags_de so the repeater shows matching rows
        $en = $this->record->tags_en ?? ($this->record->tags ?? []);
        $cs = $this->record->tags_cs ?? [];
        $de = $this->record->tags_de ?? [];

        $max = max(count($en ?? []), count($cs ?? []), count($de ?? []));
        $tags = [];

        for ($i = 0; $i < $max; $i++) {
            $tags[] = [
                'value_en' => $en[$i]['value'] ?? '',
                'value_cs' => $cs[$i]['value'] ?? '',
                'value_de' => $de[$i]['value'] ?? '',
            ];
        }

        $data['tags'] = $tags;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
