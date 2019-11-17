<?php

namespace App\Ticsol\Base\Rules;

use Illuminate\Contracts\Validation\Rule;

class HierarchyDepth implements Rule
{
    protected $parent;
    protected $maxDepth;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parent, $maxDepth)
    {
        $this->parent = $parent;
        $this->maxDepth = $maxDepth;
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

            if ($value === null || $this->parent === null) {
                return true;
            }

            return !($this->parent->depth + 1 >= $this->maxDepth);

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
        return 'Invalid :attribute, hierarchy depth limit exceeded.';
    }
}
