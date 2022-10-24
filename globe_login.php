<?php

function glb_login_logo() { ?>
  <style type="text/css">
      #login h1 a, .login h1 a {
        background-image: url(<?php echo plugins_url( '/_inc/svg/globe-logo-circle.svg', __FILE__ ); ?>);
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
