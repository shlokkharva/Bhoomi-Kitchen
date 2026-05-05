<?php
  // Start session
  session_start();

  // Load Config
  require_once __DIR__ . '/config/config.php';

  // Load Helpers
  require_once __DIR__ . '/helpers/url_helper.php';
  require_once __DIR__ . '/helpers/session_helper.php';

  // Autoload Core Libraries
  spl_autoload_register(function($className){
    require_once __DIR__ . '/core/' . $className . '.php';
  });
