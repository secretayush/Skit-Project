<?php

use App\Helpers\Session; ?>
<!-- Js of bootstrap include -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous"></script>

<script>
$(function () {
  $('#signup,#loginform,#hrforms,#job_post_form,#resetform').parsley({errorsContainer: function(el) {
    return el.$element.closest('.form-group');
  }}).on('field:validated', function(e) {
    var ok = $('.parsley-error').length === 0;
    $('.fa-exclamation-circle.'+e.element.id).toggleClass('visible', !ok);
    $('.fa-check-circle.'+e.element.id).toggleClass('visible', ok);
  })
});

const realFileBtn = document.getElementById("upload");
const customBtn = document.getElementById("custom-button");

customBtn.addEventListener("click", function() {
  realFileBtn.click();
});

realFileBtn.addEventListener("change", function() {
  if (realFileBtn.value) {
    customBtn.innerHTML = "<small>Selected File: "+realFileBtn.value.match(
      /[\/\\]([\w\d\s\.\-\(\)]+)$/)[1]+"</small>";
    customBtn.className = "btn btn-success mw-100";
  } else {
    customBtn.className = "btn btn-info";
    customBtn.innerHTML = "<small>Choose a file</small>";
  }
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

</body>
</html>
