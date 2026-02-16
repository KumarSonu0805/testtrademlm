<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $title.' | '.PROJECT_NAME; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="<?= file_url('includes2/css/style.css?v=1'); ?>" rel="stylesheet"/>
        <link rel="icon" type="image/png" href="<?= file_url('assets/images/logo.png'); ?>" />
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
        <link rel="stylesheet" href="<?= file_url('includes2/custom/custom.css?v=1.1'); ?>">
        <!--   Core JS Files   -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
   <body>
      <div id="particles-js"></div>