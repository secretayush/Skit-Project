<?php
  use App\Helpers\Session;
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exam System</title>
  <link rel="shortcut icon" href="/Images/logo.ico" type="image/x-icon">
  <link rel="icon" href="/Images/logo.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/css/exam.css">
</head>

<body>
  <?php if (Session::isset('message')) : ?>
    <div class="container mt-2">
      <div class="alert alert-<?php echo $_SESSION['type']; ?>" role="alert">
        <?php echo $_SESSION['message']; ?>
      </div>
    </div>
    <?php
    Session::unset('message');
    Session::unset('type');
    ?>
  <?php endif ?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <a class="navbar-brand"><?php echo unserialize($_SESSION['user'])->name; ?></a>
  <a class="navbar-brand">Time Left: <span class="time-left">0h 0m 0s</span></a>
  <a class="btn btn-success text-white" onclick="submitTest()">Submit</a>
</nav>

<div class="d-none exam-container container-fluid mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-9 col-lg-9">
            <div class="border">
                <div class="bg-white p-3 border-bottom">
                    <div class="d-flex flex-row justify-content-between align-items-center mcq">
                        <h4><?php echo $data['exam']->description; ?></h4><span>(<span class="current-question">0</span> of <span class="total-questions">0</span>)</span>
                    </div>
                </div>
                <form action="/student/test/<?php echo $data['exam']->exam_token; ?>" method="POST" id="exam-form">
                  <?php foreach ($data['questions'] as $key => $question): ?>
                    <div class="question q-<?php echo $key; ?> bg-white p-3 border-bottom d-none">
                        <div class="d-flex flex-row align-items-center question-title">
                            <h3 class="text-danger">Q.</h3>
                            <h5 class="mt-1 ml-2"><?php echo htmlspecialchars_decode($question->description); ?></h5>
                        </div>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" id="ans-1-<?php echo $question->id; ?>" name="ans[<?php echo $question->id; ?>]" value="1">
                              <label class="form-check-label" for="ans-1-<?php echo $question->id; ?>">
                                <?php echo htmlspecialchars_decode($question->option_one); ?>
                              </label>
                            </div>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" id="ans-2-<?php echo $question->id; ?>" name="ans[<?php echo $question->id; ?>]" value="2">
                              <label class="form-check-label" for="ans-2-<?php echo $question->id; ?>">
                                <?php echo htmlspecialchars_decode($question->option_two); ?>
                              </label>
                            </div>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" id="ans-3-<?php echo $question->id; ?>" name="ans[<?php echo $question->id; ?>]" value="3">
                              <label class="form-check-label" for="ans-3-<?php echo $question->id; ?>">
                                <?php echo htmlspecialchars_decode($question->option_three); ?>
                              </label>
                            </div>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" id="ans-4-<?php echo $question->id; ?>" name="ans[<?php echo $question->id; ?>]" value="4">
                              <label class="form-check-label" for="ans-4-<?php echo $question->id; ?>">
                                <?php echo htmlspecialchars_decode($question->option_four); ?>
                              </label>
                            </div>
                            <input type="hidden" name="question_id[]" value="<?php echo $question->id; ?>">
                    </div>
                  <?php endforeach ?>
                </form>
                <div class="d-flex flex-row justify-content-between align-items-center p-3 bg-white">
                  <button onclick="prevQuestion();" class="btn btn-primary prev-button d-flex align-items-center btn-danger" disabled type="button"><i class="fa fa-angle-left mt-1 mr-1"></i>&nbsp;previous</button>
                  <button onclick="nextQuestion();" class="btn next-button btn-primary border-success align-items-center btn-success" type="button">Next<i class="fa fa-angle-right ml-2"></i></button></div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">
          <div class="border bg-white">
            <div class="bg-white p-3 border-bottom">
                <div class="d-flex flex-row justify-content-between align-items-center mcq">
                    Question Navigator
                </div>
                <div class="bg-white pt-3">
                    <div class="row">
                      <div class="col-md-12">
                        <nav>
                          <ul class="pagination justify-content-center row">
                            <?php foreach($data['questions'] as $key => $question): ?>
                              <li class="page-item jump-<?php echo $key; ?>"><button class="page-link" onclick="jumpToQuestion(<?php echo $key; ?>);"><?php echo $key+1; ?></button></li>
                            <?php endforeach; ?>
                          </ul>
                        </nav>
                      </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="border bg-white mt-5">
            <div class="bg-white p-3 border-bottom">
                <div class="bg-white pt-3">
                    <div class="row">
                      <div class="col-md-12" style="text-align: center;">
                        <video id="cam-stream" muted autoplay></video>
                      </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<div class="fs-container">
  <div class="row d-flex justify-content-center align-items-center" style="height: 100%;">
    <div class="row">
      <div class="col-md-12">
        <p class="lead alert alert-warning text-center">Please go full screen to continue.</p>
      </div>
      <div class="col-md-12 text-center">
            <button class="btn btn-primary" onclick="goFullScreen()">Go Full Screen</button>
            <button class="btn btn-warning">End Exam</button>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
  let questionsLength = 0;
  let cameraAllowed = false;
  let warnings = {
    value: 0,
    vListener: function(val) {},
    set val(val) {
      this.value = val;
      this.vListener(val);
    },
    get val() {
      return this.value;
    },
    registerListener: function(listener) {
      this.vListener = listener;
    }
  };
  let currentQuestion = {
    value: 0,
    vListener: function(val) {},
    set val(val) {
      this.value = val;
      this.vListener(val);
    },
    get val() {
      return this.value;
    },
    registerListener: function(listener) {
      this.vListener = listener;
    }
  };
  currentQuestion.registerListener(function(val) {
    $(".current-question").text(currentQuestion.val+1);
    if(val == 0)
      $(".prev-button").attr("disabled", true);
    else
      $(".prev-button").attr("disabled", false);
    if(val+1 == questionsLength)
      $(".next-button").attr("disabled", true);
    else
      $(".next-button").attr("disabled", false);

    $(".page-item").removeClass("active");
    $(".page-item > button").attr("disabled", false);
    $(`.jump-${val}`).addClass("active");
    $(`.jump-${val} > button`).attr("disabled", true);

    $(".question").removeClass("d-block");
    $(".question").addClass("d-none");
    $(`.q-${val}`).addClass("d-block");
  });
  warnings.registerListener(function(val) {
    if(val>3) {
      alert("This test is being auto submitted because you have crossed 3 WARNIGS!");
      submitTest();
    }
  });

  function requestFullScreen(element) {
      var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;

      if (requestMethod) {
          requestMethod.call(element);
      } else if (typeof window.ActiveXObject !== "undefined") {
          var wscript = new ActiveXObject("WScript.Shell");
          if (wscript !== null) {
              wscript.SendKeys("{F11}");
          }
      }
  }

  function onFullScreenChange() {
    var fullscreenElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement;
    if(!fullscreenElement) {
      warnings.val++;
      $(".fs-container").toggleClass("d-none");
      $(".exam-container").toggleClass("d-none");
      alert(`Warning #${warnings.val}! You are exiting full screen mode, exam will end automatically after 3 warnings!`);
    }
  }

  $(document).ready(function() {
    questionsLength = <?php echo count($data['questions']); ?>;
    $(".total-questions").text(questionsLength);
    $(".current-question").text(currentQuestion.val+1);
    $(".jump-0").addClass("active");
    $(".q-0").removeClass("d-none");
    $(".q-0").addClass("d-block");
    if(currentQuestion.val+1 == questionsLength)
      $(".next-button").attr("disabled", true);

    var countDownDate = new Date(new Date("<?php echo $data['exam']->started_at; ?>").getTime() + <?php echo $data['exam']->duration; ?>*60000).getTime();

    var x = setInterval(function() {
      var now = new Date().getTime();

      var distance = countDownDate - now;

      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      $(".time-left").text(hours + "h " + minutes + "m " + seconds + "s ");
      if (distance < 0) {
        clearInterval(x);
        $(".time-left").text("EXPIRED");
        // submitTest();
      }
    }, 1000);

    $(document).on("fullscreenchange", onFullScreenChange);
    $(document).on("webkitfullscreenchange", onFullScreenChange);
    $(document).on("mozfullscreenchange", onFullScreenChange);

    const video = document.querySelector('#cam-stream');

    window.navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.onloadedmetadata = (e) => {
                video.play();
            };
            cameraAllowed = true;
        })
        .catch( () => {
            cameraAllowed = false;
            alert('Please give camera permissions.');
        });

    window.oncontextmenu = function () {
        alert("This function is not allowed!");
        return false;
    }

    $("body").keydown(function(e){
         e.preventDefault();
         var keyCode = e.keyCode || e.which;
         if(keyCode == 123) {
            alert("This function is not allowed!");
         }
    });

    document.addEventListener( 'visibilitychange' , function() {
        if (document.hidden) {
          warnings.val++;
          $(".fs-container").toggleClass("d-none");
          $(".exam-container").toggleClass("d-none");
          alert(`Warning #${warnings.val}! You are switching tabs, exam will end automatically after 3 warnings!`);
        } else {
          $(".fs-container").toggleClass("d-none");
          $(".exam-container").toggleClass("d-none");
        }
    }, false );
  });

  function nextQuestion() {
    currentQuestion.val++;
  }

  function prevQuestion() {
    currentQuestion.val--;
  }

  function jumpToQuestion(q) {
    currentQuestion.val = q;
  }

  function goFullScreen() {
    if(cameraAllowed) {
      requestFullScreen(document.documentElement);
      $(".fs-container").toggleClass("d-none");
      $(".exam-container").toggleClass("d-none");
    } else {
      alert("Please give camera permissions to continue!");
    }
  }

  function submitTest() {
    $("#exam-form").submit();
  }
</script>

</body>
</html>
