<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?= $title.' | '.PROJECT_NAME; ?></title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <link rel="icon" href="<?= file_url('includes/img/icon.ico'); ?>" type="image/x-icon"/>

        <!-- Fonts and icons -->
        <script src="<?= file_url('includes/js/plugin/webfont/webfont.min.js'); ?>"></script>
        <script>
            WebFont.load({
                google: {"families":["Lato:300,400,700,900"]},
                custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?= file_url('includes/css/fonts.min.css'); ?>']},
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>

        <!-- CSS Files -->
        <link rel="stylesheet" href="<?= file_url('includes/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?= file_url('includes/css/atlantis.min.css'); ?>">
        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link rel="stylesheet" href="<?= file_url('includes/css/demo.css'); ?>">
        <?php
			if(!empty($styles)){
				foreach($styles as $key=>$style){
					if($key=="link"){
						if(is_array($style)){
							foreach($style as $single_style){
								echo "<link rel='stylesheet' href='$single_style'>\n\t";
							}
						}
						else{
							echo "<link rel='stylesheet' href='$style'>\n\t";
						}
					}
					elseif($key=="file"){
						if(is_array($style)){
							foreach($style as $single_style){
								echo "<link rel='stylesheet' href='".file_url("$single_style")."'>\n\t";
							}
						}
						else{
							echo "<link rel='stylesheet' href='".file_url("$style")."'>\n\t";
						}
					}
				}
			}
		?>   
        
        <!-- Custom CSS -->
        <link rel="stylesheet" href="<?= file_url('includes/custom/custom.css'); ?>">
        <!--   Core JS Files   -->
        <script src="<?= file_url('includes/js/core/jquery.3.2.1.min.js'); ?>"></script>
        <?php
            if(!empty($top_script)){
                foreach($top_script as $key=>$script){
                    if($key=="link"){
                        if(is_array($script)){
                            foreach($script as $single_script){
                                echo "<script src='$single_script'></script>\n\t";
                            }
                        }
                        else{
                            echo "<script src='$script'></script>\n\t";
                        }
                    }
                    elseif($key=="file"){
                        if(is_array($script)){
                            foreach($script as $single_script){
                                echo "<script src='".file_url("$single_script")."'></script>\n\t";
                            }
                        }
                        else{
                            echo "<script src='".file_url("$script")."'></script>\n\t";
                        }
                    }
                }
            }
        ?>
    </head>
    <?php
        if($page_type=='auth'){
    ?>
    <body class="hold-transition login-page" data-background-color="<?= CONTENT_BG ?>">
    <?php
        }
        else{
    ?>
    <body class="" data-background-color="<?= CONTENT_BG ?>">
        <div class="wrapper">
    <?php
        }
    ?>