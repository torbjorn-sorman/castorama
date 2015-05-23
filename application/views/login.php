<div class="admin-background">
  <h1>Administrering</h1>
  <?php echo validation_errors(); ?>
  <?php echo form_open('verifylogin'); ?>
    <p><input type="text" size="25" id="username" name="username" placeholder="Anv&auml;ndarnamn" /></p>    
    <p><input type="password" size="25" id="password" name="password" placeholder="L&ouml;senord" /></p>
    <p><input type="submit" value="Logga In"/></p>
  </form>
</div>