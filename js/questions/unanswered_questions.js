$(function () {
    $('.answer-button').on('click', function () {
        var id = $(this).data('question-id');
        var $question = $(this).closest('.question');
        var answer = $question.find('textarea').val();
        $.post('/questions/answer', { id: id, response: answer }, function (response) {
            if(response.error){
                alertify.error(response.error);
            } else {
                alertify.success(response);
                $question.fadeOut(1000);
            }
        })
    });
});