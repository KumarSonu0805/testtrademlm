
        <?php
            if($page_type!='auth'){
        ?>
        </div>
        <?php
            }
        ?>
        <?php
            if($this->session->flashdata('msg')!==NULL || $this->session->flashdata('err_msg')!==NULL){
                $msg=$this->session->flashdata('msg');
                $err_msg=$this->session->flashdata('err_msg');
        ?>
        <div class="notify toastr-notify d-none" data-from="top" data-align="right" data-status="<?= !empty($msg)?'success':'danger'; ?>" data-title="<?= !empty($msg)?'Success':'Error'; ?>"><?= !empty($msg)?$msg:$err_msg; ?></div>
        <?php
            }
        ?>
        <div id="body-overlay" style="display: none;"></div>

        <script src="<?= file_url('includes/js/core/popper.min.js'); ?>"></script>
        <script src="<?= file_url('includes/js/core/bootstrap.min.js'); ?>"></script>
        <!-- jQuery UI -->
        <script src="<?= file_url('includes/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js'); ?>"></script>
        <script src="<?= file_url('includes/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js'); ?>"></script>
        <!-- Bootstrap Notify -->
        <script src="<?= file_url('includes/js/plugin/bootstrap-notify/bootstrap-notify.min.js'); ?>"></script>

        <!-- jQuery Scrollbar -->
        <script src="<?= file_url('includes/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js'); ?>"></script>
        <!-- Atlantis JS -->
        <script src="<?= file_url('includes/js/atlantis.min.js'); ?>"></script>
        <!-- Atlantis DEMO methods, don't include it in your project! -->
        <script src="<?= file_url('includes/js/setting-demo2.js'); ?>"></script>
        <?php
            if(!empty($bottom_script)){
                foreach($bottom_script as $key=>$script){
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
        <!-- Custom JS -->
        <script src="<?= file_url('includes/custom/custom.js'); ?>"></script>
    </body>
</html>
