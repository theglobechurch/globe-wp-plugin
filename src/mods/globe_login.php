<?php

function glb_login_logo() {
  $logo_url = plugins_url( '../../assets/svg/globe-logo-circle.svg', __FILE__ );
  ?>
  <style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url(<?php echo $logo_url ?>);
        height:100px;
        width:100px;
        background-size: 100px 100px;
        background-repeat: no-repeat;
        padding-bottom: 0;
      }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'glb_login_logo' );

function glb_login_logo_url() {
  return 'https://globe.church';
}

add_filter( 'login_headerurl', 'glb_login_logo_url' );
