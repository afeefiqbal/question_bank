<html>
<head>
<title>Question Bank Open Ai</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<div class="sidebar" id="sidebar">
  @isset($questions)
    <a class="active" href="/"><i class="fa-solid fa-plus"></i> New Chat</a>
    @foreach($questions as $r)
      @php $syllabus = $r->user_input . ":" . $r->question->question; @endphp
      <a onclick="viewQuestion({{ $r->id }})" class="" id="list_{{$r->id}}"><i class="fa-solid fa-message"></i> {!! Str::limit($syllabus, 20, ' ...') !!}</a>

    @endforeach
  @endisset
</div>
  <div class="form-body content" id="question-bank">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items" >
                    <h3>Ask Question</h3>
                    <p></p>
                    <form class="requires-validation" id="questionForm" novalidate autocomplete="off">
                        <div class="row col-md-12">
                          <div class="col-md-7">
                              <input class="form-control" type="text" name="syllabus" placeholder="Syllabus" required>
                              <div class="invalid-feedback" id="syllabusError">This field is Required!</div>
                          </div>
                          <div class="col-md-3">
                              <select class="form-select mt-3" name="question_type" required>
                                    <option selected disabled value="">Select Question Type</option>
                                    <option value="Fill-in-the-blank">Fill-in-the-blank</option>
                                    <option value="True or False">True or False</option>
                                    <option value="Multiple Choice">Multiple Choice</option>
                              </select>
                              <div class="invalid-feedback" id="typeError">This field is Required!</div>
                          </div>
                          <div class="col-md-2">
                            <div class="form-button mt-3">
                              <button id="submit" type="submit" class="btn btn-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </div>
                    </form>

                      <div class="messages">
                        <div class="left message">
                          
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script> 
<script>

$(document).ready(function() {
    $(".validate-form").validate({
        rules: {
        syllabus:"required",
 
        },
        
    });
});
$.ajaxSetup({
    headers:
    {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
        
$('#questionForm').on('submit', function(e) {
  e.preventDefault();

  if($('#questionForm').valid() == true){

    $('#submit').html('<i class="fa fa-spinner fa-spin"></i> <span>Loading...</span>');
    $('#submit').attr("disabled", true);

    let form = $('#questionForm')[0];
    let formData = new FormData(form);
      $.ajax({
        url: '/questions/save',
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (output) {
          if(output.status == "success"){
              $(".messages > .message").last().after('<div class="right message">' +
              '<p>' + output.data['question'] + '</p>' +
              '</div>');

              $(".messages > .message").last().after('<div class="left message">' +
              '<p><pre>' +  output.data['answer'] + '</pre></p>' +
              '</div>');

              let text = output.data['syllabus']+":"+output.data['question'];
              if (text.length > 20) {
                  var truncatedText = text.substring(0, 20) + "...";
              }

              $('#sidebar').children().eq(1).before('<a><i class="fa-solid fa-message"></i> '+truncatedText+'</a>')

          }else{
              $(".messages > .message").last().after('<div class="right message">' +
              '<p> No Reult...</p>' +
              '</div>');
          }
          if(output.errors){
              $(".messages > .message").last().after('<div class="right message">' +
              '<p> Please Enter Syllabus and Question Type</p>' +
              '</div>');
          }

            $('#submit').html('<i class="fa fa-paper-plane" aria-hidden="true"></i>');
            $('#submit').attr("disabled", false);
        },
        error: function (output) {
            
        }
      });
  }
});


function viewQuestion(id)
  {
    $('#list'+id).addClass('active')
    $.ajax({
        url: '/questions/view',
        type: 'POST',
        data: {
            id:id,
        },
        success: function (output) {
            if(output.success){
                $('#question-bank').html('')
                $('#question-bank').html(output.html); 
            }
        },
        error: function (output) {
        }
    });
  }
</script>