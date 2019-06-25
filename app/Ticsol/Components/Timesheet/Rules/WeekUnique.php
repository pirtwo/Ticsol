<?php

namespace App\Ticsol\Components\Timesheet\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WeekUnique implements Rule
{

    protected $user;
    protected $year;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user, $year)
    {
        $this->user = $user;
        $this->year = $year;
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

            $timesheet = $this->user->timesheets()
                ->where('year', $this->year)
                ->where('week_number', $value)
                ->first();
            
            return $timesheet === null;

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
        return 'Invalid :attribute or the timesheet for this week has been created before.';
    }
}
