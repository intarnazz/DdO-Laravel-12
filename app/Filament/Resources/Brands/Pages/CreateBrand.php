<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use App\Models\Image;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['uploaded_image'], $data['image_name']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->handleImageSave();
    }

    private function handleImageSave(): void
    {
        $state = $this->form->getRawState();
        $uploadedImage = $state['uploaded_image'] ?? null;
        $imageName = trim($state['image_name'] ?? '');

        if (! $uploadedImage) {
            return;
        }

        $extension = strtolower(pathinfo($uploadedImage, PATHINFO_EXTENSION));
        $type = $extension === 'svg' ? 'svg' : 'img';
        $path = 'brands/' . $uploadedImage;

        if (empty($imageName)) {
            $imageName = pathinfo($uploadedImage, PATHINFO_FILENAME);
        }

        // Если имя уже занято — добавляем uuid-суффикс
        if (Image::where('name', $imageName)->exists()) {
            $imageName = $imageName . '-' . Str::uuid();
        }

        $image = Image::create([
            'name' => $imageName,
            'path' => $path,
            'type' => $type,
        ]);

        $this->record->update(['image_id' => $image->id]);
    }
}
