<?php

namespace App\Ticsol\Components\Job\Rules;

use Illuminate\Contracts\Validation\Rule;

class JobParent implements Rule
{
    protected $profile;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($profile)
    {
        $this->profile = $profile;
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

            if ($this->profile === null) {
                return false;
            }           

            $jobs = $this->profile
                ->parent
                ->jobs()
                ->get();

            return $jobs->firstWhere('id', $value) !== null;

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
        return "Invalid :attribute, profile dose't match with job parent.";
    }
}
