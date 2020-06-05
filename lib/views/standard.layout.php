<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<title><?php echo $title ?></title>
 <link rel="stylesheet" href="/css/standard.css" />
</head>
<body>

<div id="main">
<nav>
<ul>
  <li><a href='/'>Home</a></li>
  <li><a href='/cart'>Cart</a></li>
  <li><a href='/signin'>Sign in</a></li>
  <li><a href='/signup'>Sign up</a></li>
  <li><a href='/change_password'>Change password</a></li>
  <li><a href='/admin_signin'>Admin</a></li>
  <li><a href='/signout'>Sign out</a></li>
</ul>
</nav>


<div id='content'>
<?php
  if(!empty($flash)){
    echo "<p class='flash'>{$flash}</p>";
  }
  if(!empty($error)){
    echo "<p class='flash'>{$error}</p>";
  }
  require $content;
?>
</div> <!-- end content -->

</div> <!-- end main -->

</body>
</html>
