<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in for your shift</title>
  <!-- CSS only -->
<link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css" integrity="sha512-xX2rYBFJSj86W54Fyv1de80DWBq7zYLn2z0I9bIhQG+rxIF6XVJUpdGnsNHWRa6AvP89vtFupEPDP8eZAtu9qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

  <div class="container">
    <div class="row mt-4">
      <div class="col-md-3">&nbsp;</div>
      <div class="col-md-6">
        <form action="" method="post">
          <h3 class="text-center">Log In to start your shift</h3>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>

          <center><button type="submit" id="login_btn" class="btn btn-primary mt-3">Log In</button></center>
        </form>
      </div>
    </div>
  </div>
  
  <!-- JavaScript Bundle with Popper -->
  <script src="//code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/fontawesome.min.js" integrity="sha512-5qbIAL4qJ/FSsWfIq5Pd0qbqoZpk5NcUVeAAREV2Li4EKzyJDEGlADHhHOSSCw0tHP7z3Q4hNHJXa81P92borQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11.4.17/dist/sweetalert2.all.min.js" integrity="sha256-RhRrbx+dLJ7yhikmlbEyQjEaFMSutv6AzLv3m6mQ6PQ=" crossorigin="anonymous"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js" integrity="sha512-wT7uPE7tOP6w4o28u1DN775jYjHQApdBnib5Pho4RB0Pgd9y7eSkAV1BTqQydupYDB9GBhTcQQzyNMPMV3cAew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    document.querySelector('#login_btn').addEventListener('click', function (e) {
      $('#login_btn').html('<i class="fa fa-spinner fa-spin"></i> Loading...');

      e.preventDefault();
      let email = $('#email').val();
      console.log(email);
      let headers = new Headers();
        // headers.append("Authorization", "Bearer " + auth);
        headers.append("Content-Type", "application/json");
        let raw = JSON.stringify({
            "email": email
        });

        let requestOptions = {
            method: 'POST',
            headers: headers,
            body: raw,
            redirect: 'follow'
        };

        fetch("/api/startshift", requestOptions)
            .then(response => response.json())
            .then(data => {
                $("#login_btn").html('<i class="fa fa-spinner fa-spin"></i> Redirecting...');
                console.log(data.status)
                if (data.status == true) {
                  setCookie('shift_email', email, 1);
                  // Get the shift details to get the 
                  fetch("/api/getshift", requestOptions)
                    .then(response => response.json())
                    .then(result => {
                      // Store the results in arrays for the next page
                      setCookie('shift_name',result.status.name);                     
                      setCookie('shift',result.status.shift);                      
                      setCookie('shift_start', result.status.start);
                      setCookie('shift_end', result.status.stop);
                      console.log(result)
                      window.location.href = "/viewshift";
                    })
                    .catch(error => console.log('error', error));

                } else {
                    console.log(`Error: ${data}`);
                }
            })
            .catch(error => console.log('error', error));
    })

    /**
     * Small function to makr cookies
     */
    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
  </script>
</body>
</html>