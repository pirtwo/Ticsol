<?php

namespace App\Ticsol\Base\Rules;

use Illuminate\Contracts\Validation\Rule;

class HierarchyFatherMove implements Rule
{

    protected $item;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {

            if ($value === null) {
                return true;
            }

            return !$this->item->childs()->count() > 0;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Invalid :attribute, Can't move a hierarchy node with child.";
    }
}
