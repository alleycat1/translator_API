<?php
// session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php //if ($keywords == '')
    //$keywords = 'audio dictionary,talking dictionary,picture dictionary,english,ESL,EFL,Russian,Chinese,German,French,Italian,Japanese, Portuguese,Spanish';
//echo 'Keywords = ';
//echo ''.$keywords.'';
//echo '<p>';
include_once ('favicons.php');

//echo 'in header.php';


function display_meta_court($keywords)
	{
//echo 'Keywords = ';
//echo ''.$keywords.'';
//echo '<p>';	
   ?>	
   
    <title><?php echo $keywords ?></title>
	<meta name="description" content="<?php echo $keywords ?>" /> 
	<meta name="keywords" content="<?php echo $keywords ?>" /> 

        <?php return; 
    }
//$keywords = 'Holiday in Brazil';
  //  display_meta_court($keywords); ?>	

	<meta name="author" content="nEwBe and Chips" />
	<meta name="copyright" content="Copyright (c) 2006 - Word-Pal" />
	<meta name="robots" content="index, follow" />
	<meta name="ROBOT" content="index, follow" />
	<meta name="MSSmartTagsPreventParsing" content="TRUE" />	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
	<link rel="stylesheet" media="screen" type="text/css" href="/css/main.css" />
	<link rel="SHORTCUT ICON" href="/favicon.ico" />
		
	<style type="text/css">
img {behavior: url("/js/pngbehavior.htc");}</style>
	
</head>
<body>
	
<?php

// if (($_SESSION['Flash'] <> 'FlashOkay') && ($_SESSION['Flash'] <> 'FlashNotOkay')) 
//  {include("flashtester.php");}

include("flashtester.php");
require_once("ini.php");
// session_start();
 
require_once("functions.php");
// session_start();

// echo 'header.php - $_SESSION[username] = ';
// echo ''.$_SESSION['username'].'';
// echo '<p>';
	
// echo 'in header.php, going to include_log/session.php';
// echo '<p>';

include("include_log/session.php");

// echo 'in header.php, back from include_log/session.php';
// echo '<p>';

 if (@$g["lang"] && (@$g["lang"] <> ''))
	chooselang($g["lang"]);
 if (!@$_SESSION["LangS"])
	$_SESSION["LangS"] = "American";
 if (!@$_SESSION["LangL"])
	$_SESSION["LangL"] = "American";
 if (!@$_SESSION["WordPalAudio"])
	$_SESSION["WordPalAudio"] = "English-USA";
	
// Tried commenting the following line....
	//	scuko(&$english,&$russian,&$german,&$french,&$italian,&$japan,&$korean,&$chinat,&$chinas,&$othlang,&$user_online,&$forgot,&$dir);
//      This doesn't seem to do anything...maybe need more consequence code
//	$_SESSION_gc_maxlifetime=120;  //  after 2 minutes, session ends,

//    if (@$_SESSION['Login']) 
//    {
//	session_timeout();
//    }	 
 
?>
 
<!-- <! DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> -->
  

<div id="wrapper">

	<div id="up_menu">
		<ul id="nav">
<?php
//Is this working? - session -login = nothing?
        require_once("functions.php");
		if ((!isset($_SESSION['Login'])) ||  ($_SESSION['Login'] == 'Not-Registered User'))
		{
            //echo 'not isset session';
            //echo '<br><p>';
            require_once("ini.php");
		}
		
//		if ((!isset($_SESSION['Login'])) ||  ($_SESSION['Login'] == 'Not-Registered User'))
        if(!$session->logged_in)	  	{
			if (isset($_SESSION['LangS']))// If language is established
			{
				$i = 0;
				if (@$_SESSION["LangS"] == "Russian") {
						// For Russian we change the coding
				mysqli_query($database->connection,"SET NAMES 'utf8'");
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);
				mysqli_query($database->connection,"SET NAMES cp1251");// and back
				}
				elseif (@$_SESSION["LangS"] == "ChineseT")
				{
				mysqli_query($database->connection,"SET NAMES 'utf8'");
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);
				mysqli_query($database->connection,"SET NAMES 'utf8'"); // such coding on Hostdone.com
				}
				else
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);

				while($data= mysqli_fetch_array($result))
				{
					$arrmenu[$i] = mysqli_result($result, $i,$table_menu.".".$_SESSION['LangS']);// menu file - create menu 
					$i++;
				}
				echo '<li><a href="../news.php">'.$arrmenu[1].'</a></li><li><a href="../support.php">'.$arrmenu[2].'</a></li><li><a href="../links.php">'.$arrmenu[3].'</a></li><li><a href="../login.php"><b>'.$arrmenu[9].'</b></a></li><li><a href="../demo.php">'.$arrmenu[7].'</a></li><li><a href="../forums/" target="_blank">'.$arrmenu[8].'</a></li><li><a href="../index.php">'.$arrmenu[0].'</a></li> ';//Well type it is deduced(removed),not straight,  but it is deduced(removed).
			}
			else // or by default - Does this set the language ??? 
			{
				echo '<li><a href="../news.php">News</a></li><li><a href="../support.php">Support</a></li><li><a href="../links.php">Links</a></li><li><a href="../login.php"><b>Login</b></a></li><li><a href="../demo.php">Demo</a></li><li><a href="../forums/" target="_blank">Forum</a></li><li><a href="../index.php">Menu</a></li> ';
			}
		}
	   	else // if ($_SESSION['Login'] <> '') // authorized??
		{
		    /*
echo ''.$sessloggedin.'';
echo '<p>';
echo 'SESSION(Login) = ';
echo ''.$_SESSION['Login'].'';
echo '<BR>';
		    */

//    		echo '<li>It sees Authorized User... </li>';
//			echo '<li>'.$_SESSION['LangS'].'</li>';
//			echo '<li>Language = </li>';
			
//echo '$_SESSION[LangS] = ';
//echo ''. $_SESSION['LangS'].'';
//echo '<br>';
		
mysqli_query($database->connection,"SET NAMES 'utf8'");
				

//		$_SESSION[LangS] = 'ChineseT';
			if (isset($_SESSION['LangS']))//language given 
			{
				$i = 0;
				if (@$_SESSION["LangS"] == "Russian")
				{
				mysqli_query($database->connection,"SET NAMES utf8");
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);
				mysqli_query($database->connection,"SET NAMES SET NAMES cp1251"); // such coding on Hostdone.com
				}
				elseif (@$_SESSION["LangS"] == "ChineseT")
				{
				mysqli_query($database->connection,"SET NAMES utf8");
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);
				mysqli_query($database->connection,"SET NAMES latin1_swedish_ci"); // such coding on Hostdone.com
				}
				else
				$result = mysqli_query($database->connection,"SELECT * FROM ".$table_menu);
				while($data= mysqli_fetch_array($result))
				{
					$arrmenu[$i] = mysqli_result($result, $i,$table_menu.".".$_SESSION['LangS']);
					$i++;
				}
				
				echo '<li><a href="../news.php">'.$arrmenu[1].'</a></li><li><a href="../support.php">'.$arrmenu[2].'</a></li><li><a href="../links.php">'.$arrmenu[3].'</a></li><li><a href="../useredit.php">'.$arrmenu[4].'</a></li><li><a href="../process.php">'.$arrmenu[5].'</a></li><li><a href="../forums/" target="_blank">'.$arrmenu[8].'</a></li><li><a href="../index.php">'.$arrmenu[0].'</a></li> ';
			}
			else
			{
				echo '<li><a href="../news.php">News</a></li><li><a href="../support.php">Support</a></li><li><a href="../links.php">Links</a></li><li><a href="../useredit.php">Account Settings</a></li><li><a href="../process.php">Log out</a></li><li><a href="../forums/" target="_blank">Forum</a></li><li><a href="../index.php">Main</a></li>'; 
			}
		
			//if you want to show the statistic of user you must include this code and open function 
			mysqli_query($database->connection,"UPDATE ".$table_user." SET TimeStamp = '".date("Y-m-d H:i:s")."', LastPage = '".$_SERVER["REQUEST_URI"]."' WHERE Login = '".$_SESSION['Login']."'");//update user timestamp, lastpage,lastdate, online
//			mysql_query("UPDATE ".$table_user." SET TimeStamp = '".date("Y-m-d H:i:s")."', SID = session_id(), LastPage = '".$_SERVER["REQUEST_URI"]."', Online = '1' WHERE Login = '".$_SESSION['Login']."'");//update user timestamp, lastpage,lastdate, online
			//add a new line in log-file
			writelog(@$_SESSION["Login"],@$_SESSION["Active"],@$_SERVER["HTTP_HOST"].@$_SERVER["REQUEST_URI"], @$_SERVER["HTTP_REFERER"]);
		}
//		else
//		echo '<li>'.$_SESSION['Login'].'</li>';
//		echo '<li>Session Login = </li>';
//		echo '<li>Extra Stuff, </li>';
//		{
//      		echo '<li><a href="../index.php">MainLeftover</a></li><li><a href="../news.php">NewsLeftowver</a></li><li>
//			<a href="../support.php">supportleftover</a></li><li><a href="../links.php">'.$arrmenu[3].'</a></li><li><a href="../cabinet.php">'.$arrmenu[4].'</a></li><li><a href="../logout.php">'.$arrmenu[5].'</a></li><li><a href="../forums/" target="_blank">'.$arrmenu[8].'</a></li> ';
//		}
			
	?>
		</ul>
	</div>

	<div id="qqq">	
		<div id="wordog"><img src="/images/wordog.png" width="182" height="263" alt="" /></div>
		<div id="name"><a href="/"><img src="/images/name.png" width="333" height="88" alt="Word-Pal :: Dictionary In Pics" border="0" /></a></div>
		<div id="books"><img src="/images/books.jpg" width="200" height="162" alt="" /></div>
	</div>

	<div id="yellow_up">&nbsp;</div>

	<div id="body">
		<div id="right_menu">
			<div id="menu_add">
		
				<div id="language">
					<h1>Choose interface language:</h1>
	
					<!--					<a href="?lang=Ru"><img src="/images/language/russian.gif" alt="Switch to Russian" /><br>Russian</a> -->
					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=De"><img src="/images/language/germ.gif" alt="Switch to German" /><br>German</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=De"><img src="/images/language/germ.gif" alt="Switch to German" /><br>German</a>';?></div>
					
					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=Fr"><img src="/images/language/franch.gif" alt="Switch to French" /><br>French</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=Fr"><img src="/images/language/franch.gif" alt="Switch to French" /><br>French</a>';?></div>
					
					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=ChT"><img src="/images/language/chinese.gif" alt="Switch to Chinese" /><br>Chinese</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=ChT"><img src="/images/language/chinese.gif" alt="Switch to Chinese" /><br>Chinese</a>';?></div>
					
					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=Ru"><img src="/images/language/russian.gif" alt="Switch to Russian" /><br>Russian</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=Ru"><img src="/images/language/russian.gif" alt="Switch to Russian" /><br>Russian</a>';?></div>

					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=Br"><img src="/images/language/english.gif" alt="Switch to British English" /><br>English</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=Br"><img src="/images/language/english.gif" alt="Switch to British English" /><br>English</a>';?></div>

					<div class="langchoose"><?php
				if (!@$g["st"])
					echo '<a href="?t='.$g["t"].'&lang=Am"><img src="/images/language/american.gif" alt="Switch to American English" /><br>English</a>';
                else
					echo '<a href="?t='.$g["t"].'&st='.$g["st"].'&lang=Am"><img src="/images/language/american.gif" alt="Switch to American English" /><br>English</a>';?></div>
					
					<!-- <div class="langchoose"><a href="<?php //=$_SERVER["php_SELF"]?>?lang=Am"><img src="/images/language/american.gif" alt="Switch to American" /><br>American</a></div>	 -->
					
					
					<div id="nofloat"></div>

				</div>	

<?php 	                 

//check_auto_logout();

//echo 'header.php - OnlineFlag = ';
//echo ''.$OnlineFlag.'';
//echo '<p>';

//echo 'header.php - $_SESSION[Login] = ';
//echo ''.$_SESSION['Login'].'';
//echo '<p>';

//	$result = mysql_query("SELECT SID, TimeType, Online, TimeStamp FROM wpuser WHERE Login = '".$_SESSION['Login']."'");
//	//or where login ID matches ??
//	$row = mysqli_fetch_array($result);
//    $OnlineFlag = $row["Online"];
//    $_SESSION['Online'] = $row["Online"];

//   if (((@$_SESSION['Login'] != 'Not-Registered User') AND (@$_SESSION['Login'] != '')) && ($_SESSION['Online'] = 1)) 
   //If logged in...
//$sessloggedin = $session->logged_in;
//echo 'in header 8 sessloggedin = ';
//echo ''.$sessloggedin.'';
//echo '<p>';
   if($session->logged_in)
   {
//echo 'Online and Logged in - header.php';
//echo '<p>';
//echo 'Going to menu.php';
//echo '<p>';

    echo '<hr>';
	require_once("search.php");
	echo '<hr>';
    require_once("SearchGoogleDefinitionsBox1.php");
    echo '<hr>';
	require_once("menu.php");
	echo '<hr>';
	require_once("profile.php");
    echo '<hr>';
    require_once("SearchGoogleAllBox.php");
   }
   else //If not logged in...
   {
//echo 'Not logged in - header.php';
//echo '<p>';
		echo '<br>';
		echo '<div id="login"><h1><a href="/register.php" style="color: red;">Register</a></h1></div>';
		echo '<hr>';
		require_once("search.php");
    	echo '<hr>';
        require_once("SearchGoogleDefinitionsBox1.php");
	    echo '<hr>';
	    require_once("menu.php");
	    echo '<hr>';
        require_once("SearchGoogleAllBox.php");
	} ?>
			</div>	
		</div>	

<!--</div> -->
		<div style="clear: left;"></div>

<?php // session_start(); 	
  //if (($_SESSION['Flash'] <> 'FlashOkay') && ($_SESSION['Flash'] <> 'FlashNotOkay')) {include("flashtester.php");} ?>

 </body>
</html>	
	<!--full end header-->