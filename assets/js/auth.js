
/*this function handles the validation of the input in the login/signup page */
document.addEventListener("DOMContentLoaded", function(){
    /* these parameters are created to figure out which form inputs should be checked by the script */
    const signupform = document.getElementById("signup-form");
    const loginform = document.getElementById("login-form");

    /* if the signup form got submitted the script will grab the user input */
    if(signupform){
        signupform.addEventListener("submit",function(event){
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const terms = document.getElementById("terms").checked;

            /* this will make sure the password fits the pattern and is 6 characters long at least */
            const passwordregex = /^(?=.*[A-Z])(?=.*\d).{6,}$/;
            
            /*this makes sure the email address is valid */
            if(!email.includes("@") || email.length < 5){
                alert("Please enter a valid email address.");
                event.preventDefault();
            }
            /*this will make sure that the password is valid and follows the pattern */
            if(!passwordregex.test(password)){
                alert("Password must be at least 6 characters long, include at least 1 upper case letter and 1 number.");
                event.preventDefault();
            }
            /* this makes sure the user has agreed to the terms and conditions - the checkbox */
            if(!terms){
                alert("You must agree to the terms and conditions.");
                event.preventDefault();
            }
        });
    }
        /* in case the login form is submitted */
        if(loginform){
            loginform.addEventListener("submit", function(event){
                /* the script will grab the input for the username and password*/
                const username = document.getElementById("login-username").value;
                const password = document.getElementById("login-password").value;

                /* if one of the input fields is empty it will show an error */
                if(username.trim() === "" || password.trim() === ""){
                    alert("Both fields are required to login.");
                    event.preventDefault();
                }
            });
        }
});