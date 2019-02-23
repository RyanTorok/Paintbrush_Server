let loginState = 0;
let loginBlock;
$(document).ready(function () {

    let displayingOSList = false;

    loginBlock = $("#loginBlock");
    loginBlock.css("display", "none");
    $("#login").click(function () {
        setInvalidMsg("");
        if (loginState === 1) {
            $("#login").css("font-style", "normal");
            loginState = 0;
            loginBlock.css("display", "none");
        } else {
            if (displayingOSList) {
                $("#downloadArrow").click();
            }
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
            if (displayingOSList) {
                $("#downloadArrow").click();
            }
            loginBlock.css("display", "");
            $("#login").css("font-style", "normal");
            $("#createAcct").css("font-style", "italic");
            $(".createAcctOnly").css("display", "");
            loginState = 2;
            loginBlock.css("height", "");
            $("#loginInvalidMessage").css("margin-top", "");
        }
    });

    $(".barMenu:not(.loginButton):not(#submitLogin)").click(function () {
        window.location = "../index.php?scroll=" + $("#barMenus").children().index($(this));
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
            if (inv != null) {
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

    $(".roundedDL:not(#downloadArrow)").hover(function () {
        $("#downloadText").css("text-decoration", "underline");
    }, function () {
        $("#downloadText").css("text-decoration", "none");
    });


    $("#downloadArrow").click(function () {
        if (loginState === 1) {
            $("#login").click();
        } else if (loginState === 2) {
            $("#createAcct").click();
        }
        if (displayingOSList) {
            $('#osList').css("display", "none");
        } else {
            $('#osList').css("display", "");
        }
        displayingOSList = !displayingOSList;
    });

    $('#osList').css("display", "none");

    //initialize OS radios
    let system = getOS();
    switch (system) {
        case "Windows":
            $("#winR").click();
            break;
        case "Mac OS":
            $("#macR").click();
            break;
        case "Linux":
            $("#linuxtarR").click();
            break;
        case "Android":
            break;
        case "iOS":
            break;
        default:
            break;
    }
    
    $("#downloadButton").click(function () {
        let addr;
        let platform = $('input[name=os]:checked', '#pickOSRadios').val();
        console.log(platform);
        switch (platform) {
            case "win": window.open("./exedummy.exe"); break;
            case "mac": window.open("./appdummy.app"); break;
            case "tar": window.open("./tardummy.tar.gz"); break;
            case "rpm": window.open("./rpmdummy.rpm"); break;
        }
    });

    init = false;

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
        return "Please enter a username.";
    if (password.length === 0)
        return "Please enter a password.";
    if (firstName.length === 0)
        return "Please enter your first name.";
    if (lastName.length === 0)
        return "Please enter your last name.";
    if (email.length === 0)
        return "Please enter an email address..";

    //magic here
    return null;
}

function login() {
    let username = $("#usernameField").text(),
        password = $("#passwordField").text();

    return "Invalid username or password"
}

function flashBar(count) {
    console.log("hello!");
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

function getOS() {
    var userAgent = window.navigator.userAgent,
        platform = window.navigator.platform,
        macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
        windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
        iosPlatforms = ['iPhone', 'iPad', 'iPod'],
        os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = 'Mac OS';
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = 'iOS';
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = 'Windows';
    } else if (/Android/.test(userAgent)) {
        os = 'Android';
    } else if (!os && /Linux/.test(platform)) {
        os = 'Linux';
    }

    return os;
}