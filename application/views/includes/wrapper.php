                <?php
                    if($page_type=='auth'){
                        !empty($content)?$this->load->view($content):'';
                    }
                    else{
                ?>
                <?php
                    if(!empty($content_script)){
                        foreach($content_script as $key=>$script){
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
                
                <div class="main-panel">
                    <div class="content">
                        <div class="page-inner">
                            <div class="page-header">
                                <h4 class="page-title"><?= $title; ?></h4>
                                <?php
                                if(!empty($breadcrumbs)){
                                ?>
                                <ul class="breadcrumbs">
                                    <?php
                                    if(!isset($breadcrumb['active']) && $this->uri->segment(1)!=''){ $breadcrumb['active']=$title; }
                                    foreach($breadcrumb as $link=>$crumb){
                                        if($link=='active'){
                                            echo '<li class="breadcrumb-item active" aria-current="page">'.$crumb.'</li>';
                                        }
                                        else{
                                            echo '<li class="breadcrumb-item"><a href="'.base_url($link).'">'.$crumb.'</a></li>';
                                        }
                                    }	
                                    ?>
                                    <li class="nav-home">
                                        <a href="#">
                                            <i class="flaticon-home"></i>
                                        </a>
                                    </li>
                                    <li class="separator">
                                        <i class="flaticon-right-arrow"></i>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">Base</a>
                                    </li>
                                    <li class="separator">
                                        <i class="flaticon-right-arrow"></i>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">Avatars</a>
                                    </li>
                                </ul>
                                <?php
                                }
                                ?>
                            </div>
                            <?php !empty($content)?$this->load->view($content):''; ?>
                        </div>
                    </div>
                    <?php $this->load->view($footer); ?>
                </div>

                <?php
                    }
                ?>