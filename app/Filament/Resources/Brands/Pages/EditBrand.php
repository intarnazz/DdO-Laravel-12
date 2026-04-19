<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use App\Models\Image;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $imageId = $this->handleImageSave();

        if ($imageId !== null) {
            $data['image_id'] = $imageId;   // ← вот ключевой момент
        }

        unset($data['uploaded_image'], $data['image_name']);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', [
            'record' => $this->getRecord(),
        ]);
    }

    private function handleImageSave(): ?int
    {
        $state = $this->form->getRawState();
        $uploadedImage = $state['uploaded_image'] ?? null;
        $imageNameFromForm = trim($state['image_name'] ?? '');

        // Нормализация (Filament v3 иногда отдаёт массив)
        if (is_array($uploadedImage) && count($uploadedImage) > 0) {
            $uploadedImage = reset($uploadedImage);
        }

        if (! $uploadedImage instanceof TemporaryUploadedFile) {
            return null; // нового фото нет — ничего не делаем
        }

        // Данные файла
        $clientOriginalName = $uploadedImage->getClientOriginalName();
        $extension = strtolower(pathinfo($clientOriginalName, PATHINFO_EXTENSION));
        $type = $extension === 'svg' ? 'svg' : 'img';

        $imageName = $imageNameFromForm ?: pathinfo($clientOriginalName, PATHINFO_FILENAME);

        // Уникальность имени
        $existing = Image::where('name', $imageName)
            ->when($this->record->image_id, fn ($q) => $q->where('id', '!=', $this->record->image_id))
            ->exists();

        if ($existing) {
            $imageName .= '-' . Str::uuid();
        }

        // Сохраняем файл
        $storedFilename = $imageName . '.' . $extension;
        $path = $uploadedImage->storeAs('brands', $storedFilename, 'public');

        // Удаляем старое фото (файл + запись)
        if ($this->record->image_id) {
            $oldImage = Image::find($this->record->image_id);
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage->path);
                $oldImage->delete();
            }
        }

        // Создаём новую запись Image
        $image = Image::create([
            'name' => $imageName,
            'path' => $path,
            'type' => $type,
        ]);

        return $image->id;   // возвращаем id, чтобы сохранить его в Brand
    }
}
