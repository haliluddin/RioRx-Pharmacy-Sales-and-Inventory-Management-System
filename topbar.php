<style>
  .logo {
    width: 40px;
    height: 40px;
    margin-right: 15px;
  }
  .navbar-brand {
    display: flex;
    align-items: center;
  }
  .navbar .dropdown-menu {
    left: auto;
    right: 0;
  }
  .navbar .dropdown-toggle {
    cursor: pointer;
  }
  .navbar{
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top" style="padding: 3px 40px;">
  <div class="container-fluid">
    <a href="#" class="navbar-brand">
      <img src="assets/img/logo.png" alt="Logo" class="logo">
      <strong>Rio Rx Med Pharmacy</strong>
    </a>
    <div class="ml-auto">
      <div class="dropdown">
        <a class="text-black dropdown-toggle" id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['login_name']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account_settings">
          <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
          <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<script>
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  });
</script>
