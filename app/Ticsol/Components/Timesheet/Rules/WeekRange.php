<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WeekRange implements Rule
{

    protected $locale;
    protected $year;
    protected $weekInYear;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($locale, $year)
    {
        $this->year = $year;
        $this->locale = $locale;
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

            $weekInYear = Carbon::create($this->year)
                ->locale($this->locale)
                ->weeksInYear();

            return $value > 0 && $value <= $weekInYear;

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
        return 'Invalid :attribute. Week number is out of range.';
    }
}
