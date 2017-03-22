<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 22-Mar-17
 * Time: 2:50 AM
 */

namespace Controllers;


use Models\Question;

class QuestionsController extends BaseController {

    public function unanswered_questions() {
        $questions = Question::where('response IS NULL')->get();

        self::smarty()->assign('questions', $questions);
        self::smarty()->assign('location', 'unanswered_questions');
        self::smarty()->display('questions/unanswered.tpl');
    }

    public function store() {
        $question = Question::build($_POST['question']);
        if ($question->save()) {
            self::smarty()->assign('question', $question);
            self::smarty()->display('partials/questions/question.tpl');
        } else {
            $this->render_json(['error' => 'Sorry we could not create your question at this time.']);
        }
    }

    public function answer() {
        $question = Question::find($_POST['id']);
        if ($question) {
            if ($question->answer($_POST['response'])) {
                echo 'Question answered successfully';
            } else{
                $this->render_json(['error' => "We couldn't submit your answer, please try again."]);
            }
        } else {
            $this->render_json(['error' => 'Question not found']);
        }
    }
}