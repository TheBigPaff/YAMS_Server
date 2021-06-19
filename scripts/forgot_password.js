var alertRedInput = "#e76f51";
var defaultInput = "#2a9d8f";

function userNameValidation(usernameInput) {
    var username = document.getElementById("username");
    var issueArr = [];
    if (/[-!@#$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/.test(usernameInput)) {
        issueArr.push("No special characters!");
    }
    if (issueArr.length > 0) {
        username.setCustomValidity(issueArr);
        username.style.borderColor = alertRedInput;
    } else {
        username.setCustomValidity("");
        username.style.borderColor = defaultInput;
    }
}

function passwordValidation(passwordInput) {
    var password = document.getElementById("password");
    var issueArr = [];
    if (!/^.{6,15}$/.test(passwordInput)) {
        issueArr.push("Password must be between 6-15 characters.");
    }
    // if (!/\d/.test(passwordInput)) {
    //     issueArr.push("Must contain at least one number.");
    // }
    // if (!/[a-z]/.test(passwordInput)) {
    //     issueArr.push("Must contain a lowercase letter.");
    // }
    // if (!/[A-Z]/.test(passwordInput)) {
    //     issueArr.push("Must contain an uppercase letter.");
    // }
    if (issueArr.length > 0) {
        password.setCustomValidity(issueArr.join("\n"));
        password.style.borderColor = alertRedInput;
    } else {
        password.setCustomValidity("");
        password.style.borderColor = defaultInput;
    }

    confirmPasswordValidation();
}

function confirmPasswordValidation(passwordInput) {
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");
    var issueArr = [];
    if (passwordInput != null && !/^.{6,15}$/.test(passwordInput)) {
        issueArr.push("Password must be between 6-15 characters.");
    }
    if(password.value !== confirm_password.value){
        issueArr.push("The two passwords are not the same.");
    }
    // if (!/\d/.test(passwordInput)) {
    //     issueArr.push("Must contain at least one number.");
    // }
    // if (!/[a-z]/.test(passwordInput)) {
    //     issueArr.push("Must contain a lowercase letter.");
    // }
    // if (!/[A-Z]/.test(passwordInput)) {
    //     issueArr.push("Must contain an uppercase letter.");
    // }
    if (issueArr.length > 0) {
        confirm_password.setCustomValidity(issueArr.join("\n"));
        confirm_password.style.borderColor = alertRedInput;
    } else {
        confirm_password.setCustomValidity("");
        confirm_password.style.borderColor = defaultInput;
    }
}