<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidWeekStart implements Rule
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
        try {

            $weekStart = Carbon::create($value)
                ->locale($this->locale);

            return $weekStart->year == $this->year &&
                $weekStart->week() == $this->weekNumber &&
                $weekStart->shortDayName == 'Mon';

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
        return 'Invalid :attribute, Week start should be Monday.';
    }
}
