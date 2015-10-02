<?php /** @var LoginUserViewModel $model */ ?>
<h1>
    Login
</h1>
<?php
     if(count($this->model->errors) > 0) {
         echo '<div  class="errors">';
         foreach ($this->model->errors as $error) {
             echo '<p>' . $error . '</p>';
         }

         echo '</div>';
     }
?>
<div class="login">
    <form method="post">
        <input type="text" name="username" placeholder="Username" /><br/>
        <input type="password" name="pass" placeholder="Password" /><br/>
        <input type="submit" name="submit" value="Login" />
    </form>
</div>