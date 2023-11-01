<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionRequest extends Model
{
    use HasFactory;

    protected $table = "requests";

    protected $fillable = ['id', 'user_input', 'question_type', 'assistant_output', 'input_token', 'output_token'];

    public function question()
    {
        return $this->hasOne(Question::class, 'request_table_id', 'id');
    }
}
