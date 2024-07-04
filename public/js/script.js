      function attemptLogin() {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        if (username === "your_username" && password === "your_password") {
          document.getElementById("successAlert").style.display = "block";
          setTimeout(function () {
            window.location.href = "success.html";
          }, 2000);
        } else {
          document.getElementById("errorAlert").style.display = "block";
        }
      }
