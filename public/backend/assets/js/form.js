var currentTab = 0;
document.addEventListener("DOMContentLoaded", function(event) {
    showTab(currentTab);

});

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        $('#nextBtn').html("Submit")
        $("#nextBtn").removeAttr("type");
        $("#nextBtn").removeAttr("class");
        $('#nextBtn').attr('class', 'btn btn-lg btn-success')
        $('#nextBtn').attr('type', 'submit')

    } else {
        $('#nextBtn').html("Next");
        $("#nextBtn").removeAttr("type");
        $("#nextBtn").removeAttr("class");
        $('#nextBtn').attr('class', 'btn btn-lg btn-primary')
        $('#nextBtn').attr('type', 'button')
    }
    fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (n == 1 && !validateForm()) return false;
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
        document.getElementById("prevBtn").style.display = "none";
        document.getElementById("nextBtn").style.display = "none";
        document.getElementById("success-msg").style.display = "block";

        alert("Something")
        document.getElementById("regForm").submit();





    }
    showTab(currentTab);
}

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    for (i = 0; i < y.length; i++) {
        if (y[i].value == "") {
            y[i].className += " invalid";
            $('#error').html("All fields must be filled")
            $('#error').attr('class', 'alert alert-danger')
            valid = false;
        }
    }
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";
}