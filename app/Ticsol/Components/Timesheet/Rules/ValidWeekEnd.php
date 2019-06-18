<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidWeekEnd implements Rule
{

    protected $locale;
    protected $year;
    protected $weekNumber;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($locale, $year, $weekNumber)
    {
        $this->year = $year;
        $this->locale = $locale;
        $this->weekNumber = $weekNumber;
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
        $weekEnd = Carbon::create($this->year)
            ->locale($this->locale)
            ->week($this->weekNumber)
            ->endOfWeek()
            ->format('Y-m-d');
            
        return $value === $weekEnd;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not a valid week end for locale:' .
        $this->locale . ' and week number:' . $this->weekNumber;
    }
}
