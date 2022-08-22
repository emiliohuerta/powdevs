
<form class="row g-3 needs-validation" action="/users/login" method="post" novalidate>
    <div class="mb-3  ">
        <label for="exampleFormControlInput1" class="form-label">Username</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" required placeholder="admin" name="User[username]">
        <div class="valid-feedback">
        Looks good!
        </div>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput2" class="form-label">Password</label>
        <input class="form-control" id="exampleFormControlInput2" type="password" required name="User[password]" ></textarea>
        <div class="valid-feedback">
        Looks good!
        </div>
    </div>

    <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit form</button>
    </div>
    

</form>




<script>


(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>