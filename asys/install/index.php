<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
include '../DATA/includes/system/aboutversion.php';
include '../DATA/includes/system/system.php';
function create_tables($url,$nowhost,$nowdatabase,$nowuser,$nowpass){
	$link = @mysql_connect($nowhost, $nowuser, $nowpass);
	if (!$link) {
		echo '<div class="bad"> Could not connect: ' . mysql_error() . '</div>';
	}else{
		$db_selected = @mysql_select_db($nowdatabase, $link);
		if (!$db_selected) {
			echo '<div class="bad"> Database not found : ' . mysql_error() . '</div>';
		}else{
			include $url;
			return true;
		}
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<meta http-equiv="content-type"
	content="application/xhtml+xml; charset=utf-8" />
<meta name="generator" content="Adminsystems Installation Manager 1.0" />
<link rel="stylesheet" type="text/css" href="style.css" title="style" />

<title>Adminsystems <?php echo $asys['asys_version']; ?> Installation</title>
</head>
<body>
	<div id="navbar">
		<h3>Installation</h3>
		<a href="index.php"
		<?php if(!isset($_GET['page'])) echo 'class="current" '?>>home</a> <a
			href="index.php?page=install"
			<?php if(isset($_GET['page']) AND $_GET['page'] == 'install') echo 'class="current" '?>>install</a>
		<a href="index.php?page=update"
		<?php if(isset($_GET['page']) AND $_GET['page'] == 'update') echo 'class="current" '?>>upgrade</a>
	</div>

	<?php
	if(!isset($_GET['page'])){
		$_GET['page'] = 'home';
	}
	if($_GET['page'] != 'install' AND $_GET['page'] != 'update'){
		?>
	<div id="main">
		<h2>
			Adminsystems
			<?php echo $asys['asys_version'];?>
			Installation
		</h2>
		<h3>welcome</h3>
		<p>Welcome to the Adminsystems Installation. Please, read the
			instructions.</p>

			<?php
			if(is_file('../../asys_conf/upgrade')){
				?>
		<b><font color=red>Your Adminsystems Installation needs an Upgrade.
			Please, go to the upgrade page in the menu.</font> </b>
			<?php
			}
			?>
		<h3>system requirements</h3>
		<p>Please make sure you meet this requirements</p>
		<p>
			needed:
			<div class="good">web server installed</div>
			<div class="good">
				php version
				<?php echo phpversion()?>
				installed
			</div>
			<?php if(extension_loaded('mysql')){echo '<div class="good">mysql installed</div>';}else{echo '<div class="bad">mysql NOT installed</div>';}?>
			<?php if(is_writable('../../asys_conf')){echo '<div class="good">asys_conf directory is writeable</div>';}else{echo '<div class="bad">asys_conf directory is NOT writeable</div>';}?>
			<?php if(is_writable('../../asys_conf/db_conf.php') OR !is_file('../../asys_conf/db_conf.php')){echo '<div class="good">db_conf.php is writeable</div>';}else{echo '<div class="bad">asys_conf/db_conf.php is NOT writable</div>';}?>
			<?php if(is_writable('../../upload')){echo '<div class="good">upload directory is writeable</div>';}else{echo '<div class="bad">upload directory is NOT writeable</div>';}?>
		</p>
		<p>
			additional:
			<?php if(extension_loaded('mysqli')){echo '<div class="good">mysqli installed</div>';}else{echo '<div class="bad">mysqli NOT installed</div>';}?>
		</p>
		<h3>install instructions</h3>
		<p>When you meet the requirements, you can click install in the
			navigation. fill in the database user informations and start the
			installation.</p>
		<h3>update instructions</h3>
		<p>If you want to update your adminsystems installation click on
			upgrade.</p>
	</div>
	<?php
	}

	if($_GET['page'] == 'install' AND is_writable ('../../asys_conf')){
		if(!isset($_GET['do']) OR $_GET['do'] == 'mysql'){
			?>
	<div id="main">
		<h2>
			Adminsystems
			<?php echo $asys['asys_version'];?>
			Installation
		</h2>
		<h3>installation</h3>
		<p>please enter your mysql user information</p>
		<?php
		if(!extension_loaded('mysql') AND !extension_loaded('mysqli')){
			echo '<div class="bad">you do not have any supported database installed!</div>';
		}

		?>
		<?php if(!isset($_POST['db_server'])){
		$_POST['db_server'] = 'localhost';
		}
		?>
		<form action="index.php?page=install&amp;do=mysql" method="post">
			<h3>database informations</h3>
			<p>database type - if available, i recommend MySQLi</p>
			<select name="db_type">
			<?php if(extension_loaded('mysqli')) echo '<option value="mysqli">MySQLi</option>' . "\n";?>
			<?php if(extension_loaded('mysql')) echo '<option value="mysql">MySQL</option>' . "\n";?>
			</select>
			<p>Database server (if you don't know this, use localhost)</p>
			<input type="text" name="db_server"
				value="<?php if(isset($_POST['db_server'])) echo $_POST['db_server']?>" />
			<p>Database user (your database user account)</p>
			<input type="text" name="db_user"
				value="<?php if(isset($_POST['db_user'])) echo $_POST['db_user']?>" />
			<p>Database password (the password of your database user)</p>
			<input type="password" name="db_pass"
				value="<?php if(isset($_POST['db_pass'])) echo $_POST['db_pass']?>" />
			<p>Database name (the name of your database)</p>
			<input type="text" name="db_name"
				value="<?php if(isset($_POST['db_name'])) echo $_POST['db_name']?>" />
			<p>Database prefix (if you want to install multiple adminsystems
				installations in this database, change this)</p>
			<input type="text" name="db_prefix" value="asys" />
			<br />
			<input type="submit" value="Next" />
		</form>


		<?php
		}
		if(isset($_GET['do']) AND $_GET['do'] == 'mysql'){
			if(!is_file('../../asys_conf/installed')){
				$install = create_tables('INSTALL_SQL_FILES/asys.php',$_POST['db_server'],$_POST['db_name'],$_POST['db_user'],$_POST['db_pass']);
				$installed_file = fopen('../../asys_conf/installed', 'a');
				fwrite($installed_file, $asys['asys_version']);
				fclose($installed_file);

				$db_configuration =
'<?php
$conf[\'db_type\'] = '. '\'' . $_POST['db_type'] . '\'' . '; 
$conf[\'db_server\'] = ' . '\'' . $_POST['db_server'] . '\'' . ';
$conf[\'db_user\'] = ' . '\'' .  $_POST['db_user'] . '\'' . ';
$conf[\'db_pass\'] = ' . '\'' . $_POST['db_pass'] . '\'' .  ';
$conf[\'db_name\'] = ' . '\'' . $_POST['db_name'] . '\'' . ';
$conf[\'db_prefix\'] = ' . '\'' .$_POST['db_prefix'] . '\'' .';
?>';

				$db_conf = fopen('../../asys_conf/db_conf.php', 'w');
				fwrite($db_conf, $db_configuration);
				fclose($db_conf);

				if($install){
					echo
    '
    <h3>Installation successful</h3>
    <p><div class="good">Installation successful</div></p>
    <p><a href="index.php?page=install&amp;do=success">click here for the next step</a></p>
    ';
				}
			}else{
				echo
  '
  <h3>already installed</h3>
  <p><div class="bad">Adminsystems is already installed</div></p>
  ';
			}
		}




		echo '</div>';

		if(isset($_GET['do']) AND $_GET['do'] == 'success'){
			?>
		<div id="main">
			<h2>
				Adminsystems
				<?php echo $asys['asys_version'];?>
				successfully installed
			</h2>
			<h3>the installation was complete</h3>
			<p>
				check out your new adminsystems installation. <br /> <a
					href="../../index.php">Adminsystems Frontpage</a> <br /> <a
					href="../index.php">Adminsystems Backend</a>
			</p>
			<h3>login information</h3>
			<p>
				here are the login informations for the backend: <br /> username:
				admin <br /> password: admin <br /> please change it immediately!
			</p>
			<h3>important note</h3>
			<p>
				<div class="bad">delete the "install" folder in your adminsystems
					directory!</div>
			</p>
			<?php
		}


	}

	if($_GET['page'] == 'update'){
		?>
			<div id="main">
				<h2>Adminsystems Update Manager</h2>
				<h3>current version</h3>
				<p>
					Your current Version is:
					<?php echo $asys['asys_version']; echo ' '; echo $asys['asys_core_version'];?>
				</p>
				<h3>available upgrades</h3>
				<p>
				<?php $available_upgrades = read_dir('upgrades', $only_dirs = false);
				if(isset($available_upgrades[0])){
					foreach($available_upgrades as $upgrade){
						$versions = explode('_', $upgrade);
						echo '<a href="index.php?page=update&amp;do='. $versions[0] . '_' . str_replace('.php', '', $versions[1]) .'">Upgrade from ' . $versions[0] . ' to ' . str_replace('.php', '', $versions[1]) . '</a>';
					}}else{
						echo 'no upgrades available';
					}
					?>
				</p>


				<?php
				if(isset($_GET['do'])){
					$upgrade_file = str_replace('../', '', $_GET['do']);
					if(is_file('upgrades/' . $upgrade_file . '.php')){
						include '../../asys_conf/db_conf.php';
						$upgrade = create_tables('upgrades/' . $upgrade_file . '.php',$conf['db_server'],$conf['db_name'],$conf['db_user'],$conf['db_pass']);
						unlink('upgrades/' . $upgrade_file . '.php');
						unlink('../../asys_conf/upgrade');
					}
				}
	}
	?>
			</div>


			<p class="copyright">
				(C) Landsknecht Adminsystems 2013, Version
				<?php echo $asys['asys_version']; ?>
				,
				<?php echo $asys['asys_core_version']; ?>
				, Installation Manager 1.1
			</p>

</body>
</html>
