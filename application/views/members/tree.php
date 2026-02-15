
    <style>
        /* Increase the size of tree nodes */
        .jstree-default a.jstree-anchor {
            font-size: 18px; /* Adjust the font size as needed */
        }

        /* Increase the size of icons (if you want) */
        .jstree-default .jstree-icon {
            width: 24px; /* Adjust the width and height as needed */
            height: 24px;
        }
        .jstree-node.active{
            color: #158B07;
        }
        .jstree-node.in-active{
            color: #AC0707;
        }
    </style>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php /*?><form action="">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?php
                                                            $attributes=array("id"=>"username","Placeholder"=>"Search Member","autocomplete"=>"off");
                                                            echo create_form_input("text","username","Search Member",false,'',$attributes);  
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"><br>
                                                    <button type="submit" class="btn btn-sm btn-success" id="search">Search</button>
                                                </div>
                                            </div>
                                        </form><?php */?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="jstree" style="overflow: auto;">
                                                    <?= $htmlHierarchy; ?>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <script>
                    $(document).ready(function () {
                        $('#jstree').jstree();
                    });

                </script>
	