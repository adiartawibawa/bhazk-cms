<?php

namespace App\Services;

use App\Services\Contracts\Page;
use App\Services\Contracts\Section;
use App\Models\Form;

class FilamentCMSServices
{
    public function types(): FilamentCmsTypes
    {
        return new FilamentCmsTypes();
    }

    public function authors(): FilamentCMSAuthors
    {
        return new FilamentCMSAuthors();
    }

    public function themes(): FilamentCMSThemes
    {
        return new FilamentCMSThemes();
    }

    public function formFields(): FilamentCMSFormFields
    {
        return new FilamentCMSFormFields();
    }
}
