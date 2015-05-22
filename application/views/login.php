<div>
  <h1>LIFnews Live</h1>
  <h2>Login</h2>
  <?php echo validation_errors(); ?>
  <?php echo form_open('verifylogin'); ?>
    
    <input type="text" size="20" id="username" name="username" placeholder="Username" />
    <br/>
    
    <input type="password" size="20" id="passowrd" name="password" placeholder="Password" />
    <br/>
    <input type="submit" value="Login"/>
  </form>
</div>