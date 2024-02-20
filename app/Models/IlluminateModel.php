<?php

declare(strict_types=1);


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IlluminateModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|IlluminateModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IlluminateModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IlluminateModel query()
 * @mixin \Eloquent
 */
class IlluminateModel extends Model
{

    /**
     * @var array|string[]
     */
    protected array $richTextFields = [];

    /**
     * @var array|string[]
     */
    protected array $makeAllSearchableWith = [];

    public function getSerializer(): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    public function getRichTextFieldsSearchable(): array
    {
        return $this->richTextFields;
    }

    /**
     * @return string[]
     */
    public function getAllSearchableWith(): array
    {
        return $this->makeAllSearchableWith;
    }
}
