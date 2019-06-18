<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidWeekNumber implements Rule
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
        $weekInYear = Carbon::create($this->year)
            ->locale($this->locale)
            ->weeksInYear();

        $this->weekInYear = $weekInYear;

        return $value > 0 && $value <= $weekInYear;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid :attribute. The week number must be between 1 and ' .
        $this->weekInYear . ' for year:' . $this->year;
    }
}
