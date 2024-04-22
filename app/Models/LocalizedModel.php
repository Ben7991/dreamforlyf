<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * this model should only be used for models that has internalization support
 */
abstract class LocalizedModel extends Model
{
    protected $localizedAttributes = [];

    public function __get($attribute) {
        if (in_array($attribute, $this->localizedAttributes)) {
            $localeAttribute = $attribute."_".App::getLocale();
            return $this->{$localeAttribute};
        }

        return parent::__get($attribute);
    }
}
