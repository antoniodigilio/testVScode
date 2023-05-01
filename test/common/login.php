<?php include 'config.php'; ?>
<?php include $path . 'common/login_messages.php'; ?>
<?php include $path . 'common/function.php'; ?>
<?php include $path . 'common/header_not_logged.php'; ?>
<?php
ini_set("session.cookie_httponly", 1);
if (isset($_POST['login'])) {
  login((string)$_POST['email'], (string)$_POST['password']);
}
?>
<!-- Background Start 
https://www.coca-colaitalia.it/content/dam/one/it/it/homepage/carousel/desktop/SEIA-Homepage-1600x900.jpg
-->
<div class="fixed-background" style="background: url('https://it.coca-colahellenic.com/it/index/_jcr_content/root/carousel/item_1588540754324.coreimg.jpeg/1622740670814/home-1.jpeg') no-repeat center center fixed;background-size: cover;"></div>
<!-- Background End -->

<div class="container-fluid p-0 h-100 position-relative ">
  <div class="row g-0 h-100">
    <!-- Left Side Start -->
    <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">

    </div>
    <!-- Left Side End -->

    <!-- Right Side Start -->
    <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
      <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-50 px-5">
          <div class="mb-4">
            <a href="../index.php">
              <div class="row">
                <div class="col-md-4 col-lg-1 "></div>
                <div class="col-md-4 col-lg-12 ">
                  <center> <img src="https://it.coca-colahellenic.com/content/dam/cch/it/images/Logo-HBC.png" class="w-100"></center>
                </div>
              </div>
              <div class="row pt-3">
                <div class="col-md-3 col-lg-1"></div>
                <div class="col-md-6 col-lg-10">
                  <center> <img src="../assets/img/logo/logo_promomedia.png" class="w-100"></center>
                </div>
              </div>

              <br />
              <center> </center>
            </a>
          </div>
          <!--  <div class="mb-5">
                  <h2 class="cta-1 mb-0 text-primary">Welcome,</h2>
                  <h2 class="cta-1 text-primary">let's get started!</h2>
                </div>
                <div class="mb-5">
                  <p class="h6">Please use your credentials to login.</p>
                  <p class="h6">
                    If you are not a member, please
                    <a href="Pages.Authentication.Register.html">register</a>
                    .
                  </p>
                </div>-->
          <div>
            <form class="tooltip-end-bottom needs-validation" novalidate="novalidate" method="POST">
              <div class="text-center mb-2">
                <h5><strong>Login</strong></h5>
              </div>
              <div class="mb-3 filled form-group tooltip-end-top">
                <i data-cs-icon="user"></i>
                <input class="form-control" required placeholder="Username o e-mail" name="email"  autocomplete="off" />
                <div class="invalid-feedback">Valore non consentito!</div>
              </div>
              <div class="mb-3 filled form-group tooltip-end-top" style="position:relative;display:block">
                <i data-cs-icon="lock-off"></i>
                <input class="form-control pe-7" required name="password" type="password" placeholder="Password" autocomplete="off" id="login_pwd" />
                <div class="invalid-feedback">Valore non consentito!</div>
                <div id="show_hide_pwd">
                  <i data-cs-icon="eye-off" id="icon_pwd_hidden"></i>
                  <i data-cs-icon="eye" id="icon_pwd_visible"></i>
                </div>
              </div>
              <button type="submit" class="btn btn-lg btn-primary w-100 mb-2">Login</button>
              <div class="text-center"><a class="" href="forget_password.php">Password dimenticata</a></div>
              <input type="hidden" name="login" value="1">
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Right Side End -->
  </div>
</div>
<?php include $path . 'common/footer_not_logged.php'; ?>