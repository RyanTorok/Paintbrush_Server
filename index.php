<?php
?>

<html>
  <head>
    <title>The Paintbrush Project</title>
      <link rel="stylesheet" href="index.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
      <script type="text/javascript" src="index.js"></script>
  </head>
  <body>
    <div id="titleBarWrapper">
        <div id="titleBar" class="asRow">
            <div>
                <p id="title">paintbrush.</p>
                <p id="subtitle">Let's get something done today.</p>
            </div>
            <div id="barMenus" class="asRow">
                <p class="barMenu">About</p>
                <p class="barMenu">For Students</p>
                <p class="barMenu">For Teachers</p>
                <p class="barMenu">For Schools</p>
            </div>
            <div id="loginButtons" class="asRow" style="float: right;">
                <p id="login" class="barMenu loginButton">Sign In</p>
                <p style="color: white">|</p>
                <p id="createAcct" class="barMenu loginButton">Sign Up</p>
            </div>
            <div id="downloadButton" class="roundedDL">
                <p id="downloadText">Download</p>
            </div>
            <div id="downloadArrow" class="roundedDL">
                <p id="downloadArrowText">&#xfe40;</p>
            </div>
        </div>
        <div id="titleBarBorder">
            <div id="scrollBar"></div>
        </div>
    </div>
    <div id="mainBody">
        <div id="loginBlock">
            <div id="loginScrollBar"></div>
            <form id="loginForm">
                <label for="usernameField">Username</label><br>
                <input id="usernameField" type="text" name="username"><br>
                <label for="passwordField">Password</label><br>
                <input id="passwordField" type="password" name="password"><br>
                <label class="createAcctOnly" for="firstNameField">First Name</label><br>
                <input class="createAcctOnly" id="firstNameField" type="text" name="firstName"><br>
                <label class="createAcctOnly" for="lastNameField">Last Name</label><br>
                <input class="createAcctOnly" id="lastNameField" type="text" name="lastName"><br>
                <label class="createAcctOnly" for="emailField">Email</label><br>
                <input class="createAcctOnly" id="emailField" type="text" name="email"><br>
                <label class="createAcctOnly" for="schoolCodeField">School Code (Optional)</label><br>
                <input class="createAcctOnly" id="schoolCodeField" type="text" name="schoolCode"><br>
                <p id="loginInvalidMessage">&nbsp;</p>
                <p id="submitLogin" class="barMenu">Submit</p>
            </form>
        </div>
    </div>
  </body>
</html>
