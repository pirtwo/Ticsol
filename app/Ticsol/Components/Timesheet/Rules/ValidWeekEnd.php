<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidWeekEnd implements Rule
{

    protected $locale;
    protected $year;
    protected $week_start;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($locale, $year, $week_start)
    {
        $this->year = $year;
        $this->locale = $locale;
        $this->week_start = $week_start;
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
            $weekStart = Carbon::create($this->week_start)
                ->locale($this->locale);

            $weekEnd = Carbon::create($value)
                ->locale($this->locale);

            return $weekEnd->shortDayName == 'Sun' && $weekStart->addDays(6)->equalTo($weekEnd);

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
        return 'Invalid :attribute, Week end should be Sunday.';
    }
}
