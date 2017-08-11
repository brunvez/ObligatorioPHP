<div class="panel panel-default">
    <div class="panel-body">
        {if $question->date}
            <div class="text-muted pull-right">Asked on: {$question->date}</div>
            <br>
        {/if}
        {$question->body}
    </div>
    {if $question->response}
        <div class="panel-footer">
            <div class="text-muted pull-right clear-fix">Answered on: {$question->response_date}</div>
            <br>
            {$question->response}
        </div>
    {/if}
</div>