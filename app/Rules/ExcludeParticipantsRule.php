<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcludeParticipantsRule implements ValidationRule
{
    protected $participants;
    protected $user_status_in_project;

    public function __construct($participants, $user_status_in_project)
    {
        $this->participants = $participants;
        $this->user_status_in_project = $user_status_in_project;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $participant = $this->participants->firstWhere('user_id', $value);

        if (!$participant) {
            $fail('Пользоваетеля нет в проекте');
        }

        if ($this->user_status_in_project >= $participant->status) {
            $fail('Вы не можете исключать из проекта пользователей выше или равных вам по статусу');
        }
    }
}
