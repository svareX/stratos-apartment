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
        $en = $this->record->tags_en ?? ($this->record->tags ?? []);
        $cs = $this->record->tags_cs ?? [];
        $de = $this->record->tags_de ?? [];

        $max = max(
            is_countable($en) ? count($en) : 0,
            is_countable($cs) ? count($cs) : 0,
            is_countable($de) ? count($de) : 0
        );
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
        if (empty($data['check_in_time'])) {
            $data['check_in_time'] = $this->record->check_in_time ?? '15:00:00';
        }

        if (empty($data['check_out_time'])) {
            $data['check_out_time'] = $this->record->check_out_time ?? '10:00:00';
        }

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

            $data['tags'] = $tags_en;
        }

        return $data;
    }
}
