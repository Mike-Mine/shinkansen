<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Gate;

class TaskAttributePermission implements ValidationRule
{
    private $attributesMapping = [
        'status' => [
            'gate' => 'updateStatus',
            'name' => 'status',
        ],
        'title' => [
            'gate' => 'update',
            'name' => 'title',
        ],
        'description' => [
            'gate' => 'update',
            'name' => 'description',
        ],
        'assignee_id' => [
            'gate' => 'updateAssignee',
            'name' => 'assignee',
        ],
        'reporter_id' => [
            'gate' => 'changeReporter',
            'name' => 'reporter',
        ],
        'start_date' => [
            'gate' => 'manageDates',
            'name' => 'start date',
        ],
        'due_date' => [
            'gate' => 'manageDates',
            'name' => 'due date',
        ],
    ];

    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== $this->task->$attribute && Gate::denies($this->attributesMapping[$attribute]['gate'], $this->task)) {
            $fail("You are not allowed to update the {$this->attributesMapping[$attribute]['name']} of this task");
        }
    }
}
