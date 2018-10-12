let loginState = 0;
let loginBlock;
$(document).ready(function () {
    loginBlock = $("#loginBlock");
    loginBlock.css("display", "none");
    $("#login").click(function () {
        setInvalidMsg("");
        if (loginState === 1) {
            $("#login").css("font-style", "normal");
            loginState = 0;
            loginBlock.css("display", "none");
        } else {
            loginBlock.css("display", "");
            $("#login").css("font-style", "italic");
            $("#createAcct").css("font-style", "normal");
            loginState = 1;
            $(".createAcctOnly").css("display", "none");
            loginBlock.css("height", "12em");
            $("#loginInvalidMessage").css("margin-top", "-9.5em");
        }
    });
    $("#createAcct").click(function () {
        setInvalidMsg("");
        if (loginState === 2) {
            $("#createAcct").css("font-style", "normal");
            loginState = 0;
            loginBlock.css("display", "none");
        } else {
            loginBlock.css("display", "");
            $("#login").css("font-style", "normal");
            $("#createAcct").css("font-style", "italic");
            $(".createAcctOnly").css("display", "");
            loginState = 2;
            loginBlock.css("height", "");
            $("#loginInvalidMessage").css("margin-top", "");
        }
    });
    $(".roundedDL").hover(function () {
        $("#downloadText").css("text-decoration", "underline");
    }, function () {
        $("#downloadText").css("text-decoration", "none");
    });

    $(".barMenu:not(.loginButton):not(#submitLogin)").click(function () {
        const me = $(this);
        $( "#scrollBar" ).animate({
            left: me.offset().left,
            width: me.width()
        }, 400, function() {
            // Animation complete.
        });
    });

    $("#submitLogin").click(function () {
        flashBar(50);
        $("#login").css("font-style", "normal");
        $("#createAcct").css("font-style", "normal");
        $("#loginInvalidMessage").css("color", "transparent");
        loginBlock.css("display", "none");
        if (loginState === 1) {
            loginState = 0;
            let inv = login();
            if (inv.length > 0) {
                $("#titleBarBorder").stop(true);
                loginBlock.css("display", "");
                $("#login").css("font-style", "italic");
                $("#createAcct").css("font-style", "normal");
                loginState = 1;
                $(".createAcctOnly").css("display", "none");
                loginBlock.css("height", "12em");
                $("#loginInvalidMessage").css("margin-top", "-9.5em");
                setInvalidMsg(inv);
                $("#loginInvalidMessage").css("color", "white");
            }
        } else if (loginState === 2) {
            loginState = 0;
            let inv = createAccount();
            if (inv.length > 0) {
                $("#titleBarBorder").stop(true);
                loginBlock.css("display", "");
                $("#login").css("font-style", "normal");
                $("#createAcct").css("font-style", "italic");
                $(".createAcctOnly").css("display", "");
                loginState = 2;
                $("#loginInvalidMessage").css("margin-top", "");
                setInvalidMsg(inv);
                $("#loginInvalidMessage").css("color", "white");
                loginBlock.css("height", "");
            }
        }
    });
});

function setInvalidMsg(s) {
    if (s.length === 0) {
        $("#loginInvalidMessage").css("color", "transparent");
        $("#loginInvalidMessage").text("&nbsp;");
    } else {
        $("#loginInvalidMessage").css("color", "white");
        $("#loginInvalidMessage").text(s);
    }
}


function createAccount() {
    let username = $("#usernameField").val(),
        password = $("#passwordField").val(),
        firstName= $("#firstNameField").val(),
        lastName = $("#lastNameField").val(),
        email = $("#emailField").val(),
        schoolCode = $("#schoolCodeField").val();

    //check for emptiness
    if (username.length === 0)
        return "Please enter a username."
    if (password.length === 0)
        return "Please enter a password."
    if (firstName.length === 0)
        return "Please enter your first name."
    if (lastName.length === 0)
        return "Please enter your last name."
    if (email.length === 0)
        return "Please enter an email address.."

}

function login() {
    let username = $("#usernameField").text(),
        password = $("#passwordField").text();

    return "Invalid username or password"
}

function flashBar(count) {
    if (count === 0) {
        $("#scrollBar").css("display", "");
        return;
    }
    $("#scrollBar").css("display", "none");
    $("#titleBarBorder").animate({backgroundColor: "#aa55aa"}, 100, function () {
        $("#titleBarBorder").animate({backgroundColor: "#663366"}, 100, function () {
            flashBar(count - 1);
        });
    });
}