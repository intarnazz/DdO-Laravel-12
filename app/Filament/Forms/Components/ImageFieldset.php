<?php

namespace App\Filament\Forms\Components;

use App\Filament\Resources\Images\Schemas\ImageForm;
use Filament\Schemas\Components\Fieldset;

class ImageFieldset extends Fieldset
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->relationship('image');

        $this->schema(ImageForm::getComponents());
    }
}
