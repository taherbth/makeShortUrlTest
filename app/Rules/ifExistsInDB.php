<?php

namespace App\Rules;

use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Contracts\Validation\Rule;

class ifExistsInDB implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        if(Facilitator::where('email','=',$value)->count() != 0 || Institution::where('email','=',$value)->count() != 0 || Student::where('email','=',$value)->count() != 0){
            return true;
        }
        else return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email Does not exists';
    }
}
