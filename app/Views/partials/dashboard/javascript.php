<?php

use App\Helpers\Session; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.6.0/umd/popper.min.js" integrity="sha512-BmM0/BQlqh02wuK5Gz9yrbe7VyIVwOzD1o40yi1IsTjriX/NGF37NyXHfmFzIlMmoSIBXgqDiG1VNU6kB5dBbA==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="/javascript/bootstrap-datetimepicker.min.js"></script>
<script src="/javascript/excel-bootstrap-table-filter-bundle.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous"></script>

<script>
$(function () {
  $('#signup,#loginform,#hrforms,#job_post_form,#resetform,#moderator').parsley({errorsContainer: function(el) {
    return el.$element.closest('.form-group');
  }}).on('field:validated', function(e) {
    var ok = $('.parsley-error').length === 0;
    $('.fa-exclamation-circle.'+e.element.id).toggleClass('visible', !ok);
    $('.fa-check-circle.'+e.element.id).toggleClass('visible', ok);
  })
});

function myFunction() {
    var input, filter, cards, cardContainer, h5, title, i;
    input = document.getElementById("search_jobs");
    filter = input.value.toUpperCase();
    cardContainer = document.getElementById("myItems");
    cards = cardContainer.getElementsByClassName("job");
    for (i = 0; i < cards.length; i++) {
        title = cards[i].querySelector(".card-body h3.card-header");
        subtitle = cards[i].querySelector(".card-body h6.card-subtitle");
        if (title.innerText.toUpperCase().indexOf(filter) > -1 || subtitle.innerText.toUpperCase().indexOf(filter) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}

$(document).ready(function(){
    $('#search').keyup(function(){
        search_table($(this).val());
    });

    function search_table(value){
        $('Table tbody tr').each(function(){
            $(this).each(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value.toLowerCase())>=0)
            });
        });
    }
});

$(document).ready(function() {
      $(".exam_time").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    })

function applyCk(id) {
    $(`.ck-editor-${id}`).each((i, c) => {
        ClassicEditor.create(c);
    });
}
$(document).ready(function() {
    applyCk(1);
});
$(document).ready(function () {
    $("#addButton").click(function () {
        var id = ($('.form .question').length + 1).toString();
        $('.form').append(`<div class="question"><h3 class="text-center question-label"></h3><div class="row mb-3" id="ques"> <label for="question_1" class="col-sm-2 col-form-label col-form-label-lg">Question</label><div class="col-sm-10"><textarea class="form-control form-control-lg ck-editor-${id}" name="question[]" placeholder="Write question here..."></textarea></div></div><div class="row mb-3" id="op_1"> <label for="option_1" class="col-sm-2 col-form-label">Option 1 -</label><div class="col-sm-10"><textarea class="form-control form-control-lg ck-editor-${id}" name="op1[]" placeholder="Option 1"></textarea></div></div><div class="row mb-3" id="op_2"> <label for="option_2" class="col-sm-2 col-form-label">Option 2 -</label><div class="col-sm-10"><textarea class="form-control form-control-lg ck-editor-${id}" name="op2[]" placeholder="Option 1"></textarea></div></div><div class="row mb-3" id="op_3"> <label for="option_3" class="col-sm-2 col-form-label">Option 3 -</label><div class="col-sm-10"><textarea class="form-control form-control-lg ck-editor-${id}" name="op3[]" placeholder="Option 1"></textarea></div></div><div class="row mb-3" id="op_4"> <label for="option_4" class="col-sm-2 col-form-label">Option 4 -</label><div class="col-sm-10"><textarea class="form-control form-control-lg ck-editor-${id}" name="op4[]" placeholder="Option 1"></textarea></div></div><div class="row mb-3" id="corr_op"> <label for="colFormLabel" class="col-sm-3 col-form-label">Correct Option -</label><div class="col-sm-4"> <select name="correct_option[]" required class="form-control"><option>1</option><option>2</option><option>3</option><option>4</option> </select></div></div><hr></div>`);
        applyCk(id);
    });
    $("#removeButton").click(function () {
        if ($('.form .question').length == 0) {
            alert("No more textbox to remove");
            return false;
        }

        $(".form .question:last").remove();
    });
});
</script>
<?php if (Session::isset('message')) : ?>

  <script>
    toastr.<?php echo $_SESSION['type']; ?>("<?php echo $_SESSION['message']; ?>")
  </script>
  <?php
  Session::unset('message');
  Session::unset('type');
  ?>
<?php endif ?>
