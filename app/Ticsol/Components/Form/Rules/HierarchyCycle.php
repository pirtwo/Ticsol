<?php

namespace App\Ticsol\Components\Form\Rules;

use Illuminate\Contracts\Validation\Rule;

class HierarchyCycle implements Rule
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

            if($value == null) return true;
            if($value == $this->item->id) return false;

            $list = collect([]);
            $childs = $this->item->childs;
            if($childs)
              $list = $list->concat($childs);

            while ($list->count() > 0) {
              $elm = $list->pop();
              if($elm->id == $value)
                return false;
              if($elm->childs)
                $list = $list->concat($elm->childs);
            }

            return true;

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
        return 'Invalid :attribute, cycle in hierarchy is not allowed.';
    }
}
