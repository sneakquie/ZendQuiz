<form action="" method="POST">
	<input type="checkbox" name="test[]"/>
	<input type="checkbox" name="test[]"/>
	<input type="checkbox" name="test[]"/>
	<input type="checkbox" name="test[]"/>
	<input type="checkbox" name="test[]"/>
	<input type="submit" name="qwe"/>
</form>
<?php
if(isset($_POST['qwe']))
	var_dump($_POST);