<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>
<style>
  body {
    counter-reset: question;
  }

  .question-label:after {
    counter-increment: question;
    content: "Question "counter(question) " - ";
  }
</style>
<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 my-3">
        <div class="card shadow bg-light rounded" id="company_card">
          <h2 class="text-center" style="font-family: 'Berkshire Swash', cursive;">Create question paper</h2>
          <div class="card-body py-md-5">
            <form action="/hr/exam" method="post">
              <div class="form">
                <div class="details">
                  <div class="row mb-3" id="name">
                    <label for="name_exam" class="col-sm-2 col-form-label">Title -</label>
                    <div class="col-sm-10">
                      <input id="name" class="form-control form-control-lg col-form-label-lg"required name="name_exam" placeholder="Name of the exam...">
                    </div>
                  </div>
                  <div class="row mb-3" id="desc">
                    <label for="desc_exam" class="col-sm-2 col-form-label">Description -</label>
                    <div class="col-sm-10">
                      <textarea id="desc" class="form-control form-control-lg" required name="desc_exam" placeholder="Description of exam..."></textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="time">
                    <label for="time" class="col-sm-2 col-form-label">Time (in minutes) -</label>
                    <div class="col-sm-10">
                      <input id="time" class="form-control form-control-lg" required pattern="[1-9]+[0-9]" name="duration" placeholder="eg: 60">
                    </div>
                  </div>
                  <div class="row mb-3" id="neg">
                    <label for="neg" class="col-sm-2 col-form-label">Negative Mark -</label>
                  <div class="form-check">
                    <input class="form-control" type="radio" name="has_negative_marking" required value="0" id="negative_yes">
                    <label class="form-check-label" for="negative_yes">
                      Yes
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-control" type="radio" name="has_negative_marking" required value="1" id="negative_no" checked>
                    <label class="form-check-label" for="negative_no">
                      No
                    </label>
                  </div>
                </div>
                <div class="row mb-3" id="max_mark">
                  <label for="max_mark" class="col-sm-2 col-form-label">Buffer Time (in minutes)-</label>
                  <div class="col-sm-10">
                    <input id="max_mark" class="form-control form-control-lg" name="buffer_time" required pattern="[1-9]+[0-9]" placeholder="eg: 10">
                  </div>
                </div>
                <hr>
                <div class="question">
                  <h3 class="text-center question-label"></h3>
                  <div class="row mb-3" id="ques">
                    <label for="question_1" class="col-sm-2 col-form-label col-form-label-lg">Question</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="question[]" placeholder="Write question here..."></textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_1">
                    <label for="option_1" class="col-sm-2 col-form-label">Option 1 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op1[]" placeholder="Option 1"></textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_2">
                    <label for="option_2" class="col-sm-2 col-form-label">Option 2 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op2[]" placeholder="Option 2"></textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_3">
                    <label for="option_3" class="col-sm-2 col-form-label">Option 3 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op3[]" placeholder="Option 3"></textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_4">
                    <label for="option_4" class="col-sm-2 col-form-label">Option 4 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op4[]" placeholder="Option 4"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row mb-3" id="corr_op_1">
                  <label for="colFormLabel" class="col-sm-3 col-form-label">Correct Option -</label>
                  <div class="col-sm-4">
                    <select name="correct_option[]" required class="form-control">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                    </select>
                  </div>
                  <hr>
                </div>
                <br>
              </div>
            </div>
            <div class="text-justify m-4">
              <input type="button" class="btn btn-outline-danger" id="removeButton" value="Delete Question" />
              <input type="button" class="btn btn-outline-success" id="addButton" value="Add Question" />
            </div>
            <div class="text-center">
              <input type="submit" class="btn btn-success" name="submit" value="Submit Exam">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/footer.php';
?>
<?php
require_once $_ENV['ROOT'] . '/Views/partials/dashboard/javascript.php';
?>
