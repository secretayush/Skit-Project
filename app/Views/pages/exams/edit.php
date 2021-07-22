<?php
require $_ENV['ROOT'] . '/Views/partials/dashboard/head.php';
?>
<style>
  body {
    counter-reset: question;
  }

  .question-label:after {
    counter-increment: question;
    content: "Question " counter(question) " - ";
  }

</style>
<div class="content-wrapper">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 my-3">
        <div class="card shadow bg-light rounded" id="company_card">
          <div class="card-body py-md-5">
            <form action="/exam/edit/<?php echo $data['exam']->id; ?>" method="post">
              <h2 class="card-title text-center" style="font-family: 'Berkshire Swash', cursive;">Viewing Exam</h2>
              <div class="details">

                <div class="alert alert-warning" role="alert">
                  <b>Rejection Remarks: </b> <?php echo $data['exam']->remarks; ?>
                </div>
                <div class="row mb-3" id="desc">
                  <label for="desc_exam" class="col-sm-2 col-form-label col-form-label-lg">Title -</label>
                  <div class="col-sm-10">
                    <textarea id="desc" class="form-control form-control-lg" required name="desc_exam"><?php echo $data['exam']->name; ?></textarea>
                  </div>
                </div>
                <div class="row mb-3" id="desc">
                  <label for="desc_exam" class="col-sm-2 col-form-label col-form-label-lg">Description -</label>
                  <div class="col-sm-10">
                    <textarea id="desc" class="form-control form-control-lg" required name="desc_exam"><?php echo $data['exam']->description; ?></textarea>
                  </div>
                </div>
                <div class="row mb-3" id="time">
                  <label for="time" class="col-sm-2 col-form-label">Time (in minutes) -</label>
                  <div class="col-sm-10">
                    <input id="time" class="form-control form-control-lg" required value="<?php echo $data['exam']->duration; ?>" name="duration" placeholder="eg: 60">
                  </div>
                </div>
                <div class="row mb-3" id="neg">
                  <label for="neg" class="col-sm-2 col-form-label">Negative Mark -</label>

                    <div class="form-check">
                      <input class="form-control" type="radio" <?php echo $data['exam']->has_negative_marking == 0 ? "checked" : ""; ?> name="has_negative_marking" required value="0" id="negative_yes">
                      <label class="form-check-label" for="negative_yes">
                        Yes
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-control" type="radio" <?php echo $data['exam']->has_negative_marking == 1 ? "checked" : ""; ?> name="has_negative_marking" required value="1" id="negative_no" checked>
                      <label class="form-check-label" for="negative_no">
                        No
                      </label>
                    </div>

                </div>

                <div class="row mb-3" id="max_mark">
                  <label for="max_mark" class="col-sm-2 col-form-label">Buffer Time (in minutes)-</label>
                  <div class="col-sm-10">
                    <input id="max_mark" class="form-control form-control-lg" name="buffer_time" required value="<?php echo $data['exam']->buffer_time; ?>" placeholder="eg: 10">
                  </div>
                </div>
              </div>
              <hr>
              <?php foreach ($data['questions'] as $key => $question): ?>
                <div class="question">
                  <h3 class="text-center question-label"></h3>
                  <div class="row mb-3" id="ques">
                    <label for="question_1" class="col-sm-2 col-form-label col-form-label-lg">Question</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="question[]" placeholder="Write question here...">
                        <?php echo htmlspecialchars_decode($question->description); ?>
                      </textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_1">
                    <label for="option_1" class="col-sm-2 col-form-label">Option 1 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op1[]" placeholder="Option 1">
                        <?php echo htmlspecialchars_decode($question->option_one); ?>
                      </textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_2">
                    <label for="option_2" class="col-sm-2 col-form-label">Option 2 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op2[]" placeholder="Option 2">
                        <?php echo htmlspecialchars_decode($question->option_two); ?>
                      </textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_3">
                    <label for="option_3" class="col-sm-2 col-form-label">Option 3 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op3[]" placeholder="Option 3">
                        <?php echo htmlspecialchars_decode($question->option_three); ?>
                      </textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="op_4">
                    <label for="option_4" class="col-sm-2 col-form-label">Option 4 -</label>
                    <div class="col-sm-10">
                      <textarea class="form-control form-control-lg ck-editor-1" name="op4[]" placeholder="Option 4">
                        <?php echo htmlspecialchars_decode($question->option_four); ?>
                      </textarea>
                    </div>
                  </div>
                  <div class="row mb-3" id="corr_op">
                    <label for="colFormLabel" class="col-sm-3 col-form-label">Correct Option -</label>
                    <div class="col-sm-4">
                      <select name="correct_option[]" class="form-control">
                        <option <?php echo $question->correct_option == 1 ? 'selected' : ''; ?>>1</option>
                        <option <?php echo $question->correct_option == 2 ? 'selected' : ''; ?>>2</option>
                        <option <?php echo $question->correct_option == 3 ? 'selected' : ''; ?>>3</option>
                        <option <?php echo $question->correct_option == 4 ? 'selected' : ''; ?>>4</option>
                      </select>
                    </div>
                  </div>
                  <hr>
                </div>
              <?php endforeach ?>
              <br>
            <!-- <div class="text-justify m-4">
              <input type="button" class="btn btn-outline-danger" id="removeButton" value="Delete Question" />
              <input type="button" class="btn btn-outline-success" id="addButton" value="Add Question" />
            </div> -->
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

