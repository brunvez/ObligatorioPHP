{extends file="layouts/main.tpl"}

{block name="head"}
    <link rel="stylesheet" href="/css/unanswered_questions.css">
    <script src="/js/questions/unanswered_questions.js"></script>
{/block}

{block name="body"}
    <div class="page-header">
        <h1>Unanswered Questions</h1>
    </div>
    {foreach from=$questions item='question'}
        <div class="question">
            Question: {$question->body}<br>
            <div class="form">
                <div class="form-group">
                    <textarea class="form-control" title="Answer"></textarea>
                </div>
                <button class="btn btn-primary answer-button" data-question-id="{$question->id()}">Answer</button>
            </div>
            <hr>
        </div>
    {/foreach}
{/block}