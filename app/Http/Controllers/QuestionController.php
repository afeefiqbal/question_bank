<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionRequest;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = QuestionRequest::with('question')->orderBy('id', 'desc')->get();
        return view('welcome', compact('questions'));
    }
    public function save(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'syllabus' => 'required',
                'question_type' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            } else {
                $content = "List any 12 " . $request->question_type . " questions and answers based on " . $request->syllabus;
             
                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . env('CHAT_GPT_KEY'),
                ])->post('https://api.openai.com/v1/chat/completions', [
                    "model" => 'gpt-3.5-turbo',
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $content,
                        ],
                    ],

                ])->body();
          
                $answer = json_decode($response);
                if (isset($answer->choices[0])) {

                    $questionRequest = new QuestionRequest();
                    $questionRequest->user_input = $request->syllabus;
                    $questionRequest->question_type = $request->question_type;
                    $questionRequest->assistant_output = $answer->choices[0]->message->content;
                    $questionRequest->user_input = $request->syllabus;
                    $questionRequest->save();

                    $question = new Question();
                    $question->request_table_id = $questionRequest->id;
                    $question->question = $content;
                    $question->answer = $answer->choices[0]->message->content;
                    $question->save();

                    $data['syllabus'] = $request->syllabus;
                    $data['question'] = $content;
                    $data['answer'] = $answer->choices[0]->message->content;

                    DB::commit();
                    return response()->json(["status" => 'success', "data" => $data]);
                } else {
                    return response()->json(["status" => 'failed']);
                    DB::rollback();
                }
            }

        } catch (Exception $e) {
            dd($e);
            DB::rollback();
        }
    }
    public function view(Request $request)
    {
        $sidebarQuestions = QuestionRequest::with('question')->orderBy('id', 'desc')->get();
        $question = Question::where('request_table_id', $request->id)->first();

        $returnHTML = view('questions', compact('question'))->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }
}
