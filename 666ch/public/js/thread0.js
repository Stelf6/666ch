$( document.getElementsByClassName('threadId') ).click(function( event ) {
    event.preventDefault();

    const ReplyToAppended = '>>' + event.currentTarget.name + '>>';

    document.getElementById('thread_form_threadText').value += ReplyToAppended;
  });
