
                <div class="datatable-card">
                    <h2><?php echo $title; ?></h2>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">   
                                <?php /*?><div id="tabulator-table"></div><?php */?>
                                <div class="table-responsive">
                                    <table class="table table-condensed" id="table">
                                        <thead>
                                            <tr>
                                                <th>Sl.No.</th>
                                                <th>Member ID</th>
                                                <th>Member Name</th>
                                                <th>Sponsor ID</th>
                                                <th>Sponsor Name</th>
                                                <th>Joining Date</th>
                                                <th>Business</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if(!empty($members)){
                                                    $i=0;
                                                    foreach($members as $single){
                                            ?>
                                            <tr>
                                                <td><?= ++$i; ?></td>
                                                <td><?= $single['username'] ?></td>
                                                <td><?= $single['name'] ?></td>
                                                <td><?= $single['sponsor_id'] ?></td>
                                                <td><?= $single['sponsor_name'] ?></td>
                                                <td><?= date('d-m-Y',strtotime($single['date'])) ?></td>
                                                <td>$<?= getdeposits(array(),['id'=>$single['regid'],'role'=>'member']); ?></td>
                                            </tr>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <script>
	
		$(document).ready(function(e) {
            $('#table').DataTable();

        });
		
	</script>
    
    	
