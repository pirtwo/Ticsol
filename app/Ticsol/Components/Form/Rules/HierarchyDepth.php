<?php

namespace App\Ticsol\Components\Form\Rules;

use Illuminate\Contracts\Validation\Rule;

class HierarchyDepth implements Rule
{

    protected $form;
    protected $parent;
    protected $maxDepth;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($form, $parent, $maxDepth)
    {
        $this->form = $form;
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

            $childs = $this->form->childs()->get();            
            $maxDepth = $this->form->depth;

            while ($childs->count() > 0) {            
                $elm = $childs->pop();
                if($elm->depth > $maxDepth)
                    $maxDepth = $elm->depth;           
                if ($elm->childs()->count() > 0) {                
                    $childs = $childs->concat($elm->childs()->get());
                }
                if ($maxDepth >= 3) {
                    break;
                }
            }

            $branchDepth = $maxDepth - $this->form->depth + 1;

            if ($this->parent->depth + $branchDepth >= $this->maxDepth) {
                return false;
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
        return 'Invalid :attribute, hierarchy depth limit exceeded.';
    }
}
