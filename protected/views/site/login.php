<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>Login - <?=Yii::app()->name?></title>
		
		<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="special-page">
	<div id="container">
	<div class="header-psmk">&nbsp;</div>
	<div>
	<section id="login-box">
				
                <div class="block-border"> 
                    <div class="block-header"> 
                        <h1><?=Yii::app()->name?></h1> 
                    </div> 
                    <form method="post" class="block-content form" id="login-form">

                            
                        <p class="inline-small-label">
                            <label for="username">Pengguna</label> 
                            <input type="text" class="required text" value="" name="LoginForm[username]"> 
                        </p> 
                        <p class="inline-small-label" style="padding-bottom: 10px"> 
                            <label for="password">Kata Sandi</label> 
                            <input type="password" class="required password" value="" name="LoginForm[password]"> 
                        </p> 
                        <div class="clear">

                        </div> 
                        <div class="block-actions"> 
                            <ul class="actions-right"> 
                                <li style="margin-right: 15px;">
								<input type="submit" value="Masuk" class="button" style="width: 100px;"></li> 
                            </ul> 
                        </div> 
                    </form> 
                </div>
            </section>
			
			
		</div>
		</div>
		
    </body>
</html>