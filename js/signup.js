var lblUsername = document.getElementById("lbluser");
var lblPassword = document.getElementById("lblpass");
var lblFirst = document.getElementById("lblfirst");
var lblSecond = document.getElementById("lblsecond");

var txtUsername = document.getElementById("username");
var txtPassword = document.getElementById("password");
var txtFirstname = document.getElementById("first_name");
var txtLastname = document.getElementById("last_name");

var btnShow = document.getElementById("show");

lblUsername.addEventListener("click", function() {
    lblUsername.classList.add("small");
})

lblPassword.addEventListener("click", function() {
    lblPassword.classList.add("small");
})

lblFirst.addEventListener("click", function() {
    lblFirst.classList.add("small");
})

lblSecond.addEventListener("click", function() {
    lblSecond.classList.add("small");
})




txtUsername.addEventListener("focus", function() {
    lblUsername.classList.add("small");
})

txtPassword.addEventListener("focus", function() {
    lblPassword.classList.add("small");
})

txtFirstname.addEventListener("focus", function() {
    lblFirst.classList.add("small");
})

txtLastname.addEventListener("focus", function() {
    lblSecond.classList.add("small");
})





txtUsername.addEventListener("blur", function() {
    if (txtUsername.value == '') {
        lblUsername.classList.remove("small");
    }
});

txtPassword.addEventListener("blur", function() {
    if (txtPassword.value == '') {
        lblPassword.classList.remove("small");
    }
});

txtFirstname.addEventListener("blur", function() {
    if (txtFirstname.value == '') {
        lblFirst.classList.remove("small");
    }
});

txtLastname.addEventListener("blur", function() {
    if (txtLastname.value == '') {
        lblSecond.classList.remove("small");
    }
});





btnShow.addEventListener("click", function() {
    if (txtPassword.getAttribute("type") == "password") {
        txtPassword.setAttribute("type", "text");
        btnShow.innerText = "Hide";
    } else {
        txtPassword.setAttribute("type", "password");
        btnShow.innerText = "Show";
    }
});