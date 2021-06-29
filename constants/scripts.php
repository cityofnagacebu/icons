<!-- MDB -->
<script type="text/javascript" src="js/mdb.min.js"></script>
<!-- JQuery -->
<script src="js/jquery-3.4.1.js"></script>
<!-- Data Tables -->
  <script type="text/javascript" src="js/datatables2.min.js"></script>
  <!-- Custom scripts -->
  <script type="text/javascript" src="js/admin.js"></script>
  <script type="text/javascript" src="js/datepicker.min.js"></script>
<!-- Custom scripts -->
<script type="text/javascript">
  // start of document ready function
  $(document).ready(function(){
    //notification function
    function load_unseen_notification(view = '')
    {
      $.ajax({
        url:"fetchController.php",
        method:"POST",
        data:{view:view},
        dataType:"json",
        success:function(data)
        {
          $('.notif-list').html(data.notification);
          $('.newApplicants').html(data.new_applicants);
          $('.newScholars').html(data.new_scholars);
          $('.activeScholars').html(data.active_scholars);
          $('.inactiveScholars').html(data.inactive_scholars);
          $('.passedExam').html(data.passed);
          $('.failedExam').html(data.failed);
          $('.didNotTake').html(data.did_not_take);
          $('.logged').html(data.logged);
          $('.totalApplicants').html(data.applicants);
          if(data.unseen_notification > 0){
            $('.count').html(data.unseen_notification);
          }
        }
      });
    }

    load_unseen_notification();

    //notification bell 
    $('.dropdown').on('shown.bs.dropdown', function () {
      $('.count').html('');
      load_unseen_notification('yes');
    });
 
    //delay of notification
    setInterval(function(){ 
      load_unseen_notification();; 
    }, 5000);

    // delay of fade in alerts
    $(".alert-success").fadeOut(9000);
    $(".alert-danger").fadeOut(9000);

    // image upload
    if (window.File && window.FileList && window.FileReader) {
        $("#files").on("change", function(e) {
          $(".imgPrev").hide();
          $("<button class=\"btn btn-lg btn-primary btn-floating reset\" type=\"button\">" +
            "<i class=\"fa fa-undo\">"+"</i>" + 
            "</button>").insertAfter("#resetBtn");
            var files = e.target.files,
            filesLength = files.length;
            for (var i = 0; i < filesLength; i++) {
              var f = files[i];
              var fileReader = new FileReader();
              fileReader.onload = (function(e) {
                  var file = e.target;
                  $("<div class=\"cropped\">" +
                    "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                    "<br/>" +
                    "</div>").insertAfter("#fileView");
              });
              fileReader.readAsDataURL(f);
            }
            $(".reset").click(function(){
                $(".cropped").remove();
                $(".reset").remove();
                $(".imgPrev").show();
                document.getElementById('files').value = "";
            });
        });
    } else {
      alert("Your browser doesn't support to File API")
    }

    // application form next button
    $("#button1").click(function(){
      $("#nextInput").show("slow","swing");
      $("#firstInput").hide("slow", "swing");
    });

    // application form back button
    $("#button2").click(function(){
      $("#nextInput").hide("slow", "swing");
      $("#firstInput").show("slow", "swing");
    });

    // add schedule form create schedule button
    $("#btnAddSchedule").click(function(){
      $("#addSchedule").show("slow","swing");
      $("#hideCreateSchedule").hide("slow", "swing");
    });

    // application form back button
    $("#btnCancelSchedule").click(function(){
      $("#addSchedule").hide("slow","swing");
      $("#hideCreateSchedule").show("slow", "swing");
    });

    // admin data tables
    $('#dtApplicants').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtBatch').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtExams').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtEnrollmentStatus').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtQuestions').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtExamResults').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtSchedules').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });

    $('#dtScholars').DataTable({
      columnDefs: [
        { orderable: false, targets: 0, }
      ],
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "pageLength": -1
    });
    $('.dataTables_length').addClass('bs-select');

    $('#checkAll').click(function(){
      if($(this).is(":checked")){
        $(".checkItem").prop('checked', true);
        $(".checkItem").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem:checked').length > 1) {
          $("#btnDelete").show();
          $("#btnDetails").hide();
          $("#btnSendExam").hide();
          $("#btnSendEmail").hide(); 
        }  
      }
      else {
        $(".checkItem").prop('checked', false);
        $(".checkItem").closest('tr').removeClass("highlight", this.checked);
        $("#btnDelete").hide();
        $("#btnSendExam").hide();
        $("#btnDetails").hide();
        $("#btnSendEmail").hide();
      }
    });

    $('#checkAll2').click(function(){
      if($(this).is(":checked")){
        $(".checkItem2").prop('checked', true);
        $(".checkItem2").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem2:checked').length > 1) {
          $("#btnDelete").show();
          $("#btnViewQuestion").hide();
          $("#btnEdit").hide(); 
        }  
      }
      else {
        $(".checkItem2").prop('checked', false);
        $(".checkItem2").closest('tr').removeClass("highlight", this.checked);
        $("#btnDelete").hide();
        $("#btnViewQuestion").hide();
        $("#btnEdit").hide();
      }
    });

    $('#checkAll3').click(function(){
      if($(this).is(":checked")){
        $(".checkItem3").prop('checked', true);
        $(".checkItem3").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem3:checked').length > 1) {
          $("#btnDeleteQuestion").show();
          $("#btnViewFull").hide();
          $("#btnEditItem").hide(); 
        }  
      }
      else {
        $(".checkItem3").prop('checked', false);
        $(".checkItem3").closest('tr').removeClass("highlight", this.checked);
        $("#btnDeleteQuestion").hide();
        $("#btnViewFull").hide();
        $("#btnEditItem").hide();
      }
    });

    $('#checkAll4').click(function(){
      if($(this).is(":checked")){
        $(".checkItem4").prop('checked', true);
        $(".checkItem4").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem4:checked').length > 1) {
          $("#btnDeleteExamResults").show();
          $("#btnAddToScholars").hide(); 
        }  
      }
      else {
        $(".checkItem4").prop('checked', false);
        $(".checkItem4").closest('tr').removeClass("highlight", this.checked);
        $("#btnDeleteExamResults").hide();
        $("#btnAddToScholars").hide();
      }
    });

    $('#checkAll5').click(function(){
      if($(this).is(":checked")){
        $(".checkItem5").prop('checked', true);
        $(".checkItem5").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem5:checked').length > 1) {
          $("#btnScholarDetails").hide();
          $("#btnApprove").hide(); 
        }  
      }
      else {
        $(".checkItem5").prop('checked', false);
        $(".checkItem5").closest('tr').removeClass("highlight", this.checked);
        $("#btnScholarDetails").hide();
        $("#btnApprove").hide(); 
      }
    });

    $('#checkAll6').click(function(){
      if($(this).is(":checked")){
        $(".checkItem6").prop('checked', true);
        $(".checkItem6").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem6:checked').length > 1) {
          $("#btnEditBatch").hide(); 
        }  
      }
      else {
        $(".checkItem6").prop('checked', false);
        $(".checkItem6").closest('tr').removeClass("highlight", this.checked);
        $("#btnEditBatch").hide();
      }
    });

    $('#checkAll7').click(function(){
      if($(this).is(":checked")){
        $(".checkItem7").prop('checked', true);
        $(".checkItem7").closest('tr').toggleClass("highlight", this.checked);
        if($('.checkItem7:checked').length > 1) {
          $("#btnDeleteSchedule").show();
          $("#btnEditSchedule").hide(); 
        }  
      }
      else {
        $(".checkItem7").prop('checked', false);
        $(".checkItem7").closest('tr').removeClass("highlight", this.checked);
        $("#btnEditSchedule").hide();
        $("#btnDeleteSchedule").hide();
      }
    });

    $(".checkItem").on('change', function() {
      if($('.checkItem:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDelete").show();
        $("#btnDetails").show();
        $("#btnSendExam").show();
        $("#btnSendEmail").show();
      } else if($('.checkItem:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDelete").show();
        $("#btnDetails").hide();
        $("#btnSendExam").hide();
        $("#btnSendEmail").hide();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnDelete").hide();
        $("#btnDetails").hide();
        $("#btnSendExam").hide();
        $("#btnSendEmail").hide();
      }
    });

    $(".checkItem2").on('change', function() {
      if($('.checkItem2:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDelete").show();
        $("#btnViewQuestion").show();
        $("#btnEdit").show();
      } else if($('.checkItem2:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDelete").show();
        $("#btnViewQuestion").hide();
        $("#btnEdit").hide();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnDelete").hide();
        $("#btnViewQuestion").hide();
        $("#btnEdit").hide();
      }
    });

    $(".checkItem3").on('change', function() {
      if($('.checkItem3:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDeleteQuestion").show();
        $("#btnViewFull").show();
        $("#btnEditItem").show();
      } else if($('.checkItem3:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDeleteQuestion").show();
        $("#btnViewFull").hide();
        $("#btnEditItem").hide();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnDeleteQuestion").hide();
        $("#btnViewFull").hide();
        $("#btnEditItem").hide();
      }
    });

    $(".checkItem4").on('change', function() {
      if($('.checkItem4:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDeleteExamResults").show();
        $("#btnAddToScholars").show();
      } else if($('.checkItem4:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnDeleteExamResults").show();
        $("#btnAddToScholars").hide();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnDeleteExamResults").hide();
        $("#btnAddToScholars").hide();
      }
    });

    $(".checkItem5").on('change', function() {
      if($('.checkItem5:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnScholarDetails").show();
        $("#btnApprove").show(); 
      } else if($('.checkItem5:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnScholarDetails").hide();
        $("#btnApprove").hide(); 
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnScholarDetails").hide();
        $("#btnApprove").hide(); 
      }
    });

    $(".checkItem6").on('change', function() {
      if($('.checkItem6:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnEditBatch").show();
      } else if($('.checkItem6:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnEditBatch").hide();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnEditBatch").hide();
      }
    });

    $(".checkItem7").on('change', function() {
      if($('.checkItem7:checked').length == 1){
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnEditSchedule").show();
        $("#btnDeleteSchedule").show();
      } else if($('.checkItem7:checked').length > 1) {
        $(this).closest('tr').toggleClass("highlight", this.checked);
        $("#btnEditSchedule").hide();
        $("#btnDeleteSchedule").show();
      } else {
        $(this).closest('tr').removeClass("highlight", this.checked);
        $("#btnEditSchedule").hide();
        $("#btnDeleteSchedule").hide();
      }
    });

    $(".modalApplicants").modal('show');

    $(".modalBatch").modal('show');

    $(".modalExam").modal('show');

    $(".startModal").modal('show');

    $(".applySuccessModal").modal('show');

    $(".enrollSuccessModal").modal('show');

    $(".enrollmodal1").modal('show');

    $(".enrollmodal2").modal('show');

    $('.carousel').carousel({
      interval: false
    });

    $('.carousel-inner .carousel-item:first').addClass('active');
    $('.carousel-indicators .indicator:first').addClass('active');


    $("input:radio").on("change", function() {
      $(".zero").remove();
      var numberOfCheckedRadio = $('input:radio:checked').length;
      var totalNum = $('#totalNum').val();
      var percentBar = (numberOfCheckedRadio / totalNum)*100;
      var progressBar = percentBar + "%";
      $("<li class=\"list-inline-item zero\">"+ numberOfCheckedRadio +"</li>").insertAfter(".numChecked");
      document.getElementById("progressBar").style.width = progressBar;
      var i;
      var numTotal = $('#totalNum').val() + 1;
      for (i = 1; i < numTotal; i++) {
        if($('input:radio[name=flexRadioDefault'+i+']:checked').length == 1){
          $("#btnSlide"+i+"").addClass("bg-primary");
        }
      }
      if($('input:radio:checked').length == totalNum){
        $("#submitExam").show();
      }
    });
  });

  $('#carouselBasicExample2').on('slid', '', checkitem);  // on carousel move
  $('#carouselBasicExample2').on('slid.bs.carousel', '', checkitem); // on carousel move

  $('#startExamDialog').on('hidden.bs.modal', function () {
    checkitem();
    var applicantToken = $("#applicantToken").val();
    var examID = $("#examID").val();
    pre_exam_result(examID, applicantToken);  
    var totalMinutes = $("#totalMinutes").val(), totalSeconds = $("#totalSeconds").val(),
        display = document.querySelector('#countdownTimer');
        var time = parseInt(totalSeconds) + (60 * totalMinutes);
    startTimer(time, totalSeconds, display);
    
  });

  var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = yyyy + '-' + mm + '-' + dd;

  $('[data-toggle="datepicker"]').datepicker({
  startDate: today,
  autoHide: true,
  format:'yyyy-mm-dd',
});

  function pre_exam_result(exam, token)
    {
      $.ajax({
        url:"fetchController.php",
        method:"POST",
        data:{exam:exam, token:token}
      });
    }

  function startTimer(duration, extra, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (timer == 0) {
            $(".endModal").modal('show');
            $("#submitExam").hide();
        } else {
          --timer;
        }
    }, 1000);
  }

  function checkitem()                        // check function
  {
    var $this = $('#carouselBasicExample2');
    if($('.carousel-inner .carousel-item:first').hasClass('active')) {
        $this.children('.carousel-control-prev').hide();
        $this.children('.carousel-control-next').show();
    } else if($('.carousel-inner .carousel-item:last').hasClass('active')) {
        $this.children('.carousel-control-prev').show();
        $this.children('.carousel-control-next').hide();
    } else {
        $this.children('.carousel-control-prev').show();
        $this.children('.carousel-control-next').show();
    } 
  }

  $(document).on('keyup', '.required', function(e){
      let Disabled = true;
      var emailValue = $("#inputEmail").val();
      $(".required").each(function() {
          let value = this.value
          if ((value)&&(value.trim() !='')&&(emailValue.includes("@"))){
            Disabled = false
          }else{
            Disabled = true
            return false
          }
      });
   
      if(Disabled){
          $('.toggle-disabled').prop("disabled", true);
      }else{
          $('.toggle-disabled').prop("disabled", false);
      }
  });



  $(function () {
    $(document).tooltip()
  });

  var header = document.getElementById("myNav");
  var btns = header.getElementsByClassName("nav-link");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
    });
  }

var ctx = document.getElementById("myChart");

var myChart = new Chart(ctx, {
  type: "line",
  data: {
    labels: [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ],
    datasets: [
      {
        data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
        lineTension: 0,
        backgroundColor: "transparent",
        borderColor: "#007bff",
        borderWidth: 4,
        pointBackgroundColor: "#007bff",
      },
    ],
  },
  options: {
    scales: {
      yAxes: [
        {
          ticks: {
            beginAtZero: false,
          },
        },
      ],
    },
    legend: {
      display: false,
    },
  },
});



</script>