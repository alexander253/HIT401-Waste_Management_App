<?php
/* SET to display all warnings in development. Comment next two lines out for production mode*/
ini_set('display_errors','On');
error_reporting(E_ERROR | E_PARSE);

/* Set the path to the Application folder */
DEFINE("LIB",$_SERVER['DOCUMENT_ROOT']."/lib/");

/* SET VIEWS path */
DEFINE("VIEWS",LIB."views/");
DEFINE("PARTIALS",VIEWS."/partials");

# Paths to actual files
DEFINE("MODEL",LIB."/model.php");
DEFINE("APP",LIB."/application.php");

# Define a layout
DEFINE("LAYOUT","different");

# This inserts our application code which handles the requests and other things
require APP;

/* Here is our Controller code i.e. API if you like.  */
/* The following are just examples of how you might set up a basic app with authentication */

get("/",function($app){
   $app->force_to_http("/");
   $app->set_message("title","Darwin Art Company");
   $app->set_message("message","Welcome back");
   $app->render(LAYOUT,"home");
});


get("/signin",function($app){
   $app->force_to_http("/signin");
   $app->set_message("title","Sign in");
   require MODEL;
   try{
     if(is_authenticated()){
       $app->set_message("error","Why on earth do you want to sign in again. You are already signed in. Perhaps you want to sign out first.");
     }

   }
   catch(Exception $e){
     $app->set_message("error",$e->getMessage($app));
   }
   $app->render(LAYOUT,"signin");

});

get("/change",function($app){
   $app->force_to_http("/change");
   $app->set_message("title","Change password");
   require MODEL;
   try{
     if(is_authenticated()){
       try{
          $name = get_user_name();
          $app->set_message("name",$name);
          $id = get_user_id();
          $app->set_message("user_id",$id);
       }
       catch(Exception $e){
           $app->set_message("error","Error with retrieving name");
       }
     }
     else{
         $app->set_flash("You are not authorised to do this.");
         $app->redirect_to("/");
     }
   }
   catch(Exception $e){
     $app->set_message("error",$e->getMessage());
   }
   $app->render(LAYOUT,"change_password");

});


get("/signout",function($app){
   // should this be GET or POST or PUT?????
   $app->force_to_http("/signout");
   require MODEL;
   if(is_authenticated()){
      try{
         sign_out();
         $app->set_flash("You are now signed out.");
         $app->redirect_to("/");

      }
      catch(Exception $e){
        $app->set_flash("Something wrong with the sessions.");
        $app->redirect_to("/");
     }
   }
   else{
        $app->set_flash("You can't sign out if you are not signed in!");
        $app->redirect_to("/signin");
   }

});


post("/signup",function($app){
  require MODEL;
  try{
     if(is_authenticated() || is_db_empty()){
        $first_name = $app-> form('fname');
        $last_name = $app-> form('lname');
        $title = $app-> form('title');
        $email = $app-> form('email');
        $address = $app-> form('address');
        $city = $app-> form('city');
        $state = $app-> form('state');
        $country = $app-> form('country');
        $post_code = $app-> form('postcode');
        $phone = $app-> form('phone');
        $confirm = $app-> form('email_confirm');

  if($first_name && $last_name && $title && $email && $address && $city && $state && $country && $post_code && $phone && $confirm){
     try{
        sign_up($first_name, $last_name, $title, $email, $confirm, $address, $city, $state, $country, $post_code, $phone);
        $app->set_flash("Lovely, you are now signed up, ".htmlspecialchars(form('lname'))." Now sign in!");
     }
     catch(Exception $e){
          $app->set_flash($e->getMessage());
          $app->redirect_to("/signup");
     }
  }

  else{
     $app->set_flash("You are not signed up. Try again and don't leave any fields blank.");
     $app->redirect_to("/signup");
  }
  $app->redirect_to("/signup");
}
else{
   $app->set_flash("You are not authorised to access this resource");
   $app->redirect_to("/");
 }

}
 catch(Exception $e){
      $app->set_flash($e.getMessage());
      $app->redirect_to("/");
 }

});

post("/signin",function($app){
  $name = $app->form('name');
  $password = $app->form('password');
  if($name && $password){
    require MODEL;
    try{
       sign_in($email,$password);
    }
    catch(Exception $e){
      $app->set_flash("Could not sign you in. Try again. {$e->getMessage()}");
      $app->redirect_to("/signin");
    }
  }
  else{
       $app->set_flash("Something wrong with name or password. Try again.");
       $app->redirect_to("/signin");
   }
   $app->set_flash("Lovely, you are now signed in!");
   $app->redirect_to("/");
});

put("/change",function($app){
  // Not complete because can't handle complex routes like /change/23
  $app->set_flash("Password is changed");
  $app->redirect_to("/");
});


get("/admin",function($app){
   $app->force_to_http("/admin");
   $app->set_message("title","Art Company Adminstration");
   $app->set_message("message","Welcome, ADMIN");
   $app->render(LAYOUT,"art_admin");
});

# The Delete call back is left for you to work out

// New. If it get this far then page not found
resolve();
