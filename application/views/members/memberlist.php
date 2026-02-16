
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title; ?></h3>
                    </div>
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
                                                <?php if($this->session->role=='admin'){ ?>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Amount</th>
                                                <?php
                                                    }
                                                ?>
                                                <th>Wallet Address</th>
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
                                                <?php if($this->session->role=='admin'){ ?>
                                                <td><?= $single['mobile'] ?></td>
                                                <td><?//= $single['email'] ?></td>
                                                <td><?= $single['package'] ?></td>
                                                <?php
                                                    }
                                                ?>
                                                <td><?= $single['wallet_address'] ?></td>
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
            $('#table').dataTable();
            
            var url="<?= base_url('members/getmembers/?type='.$type); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        type: "auto"
                    },
                    { title: "Member ID", field: "username" },
                    { title: "Member Name", field: "name" },
                    { title: "Sponsor ID", field: "sponsor_id" },
                    { title: "Sponsor Name", field: "sponsor_name" },
                    { 
                        title: "Joining Date", 
                        field: "date",
                        formatter: function(cell){
                            let dateStr = cell.getValue(); // Y-m-d format
                            let formattedDate = dateStr.split("-").reverse().join("-");
                            return formattedDate;
                        }
                    },
                    <?php if($this->session->role=='admin'){ ?>
                    { title: "Mobile", field: "mobile" },
                    { title: "Email", field: "email" },
                    { title: "Amount", field: "package" },
                    { title: "Wallet Address", field: "wallet_address" },
                    <?php }else{ ?>
                    { title: "Wallet Address", field: "wallet_address", width:450 },
                    <?php } ?>
                ];

            var pagination={
                sizes:[10, 20, 50, 100]
            }

            //var table=createTabulator('tabulator-table',url,columns,pagination);

            function refreshTableData() {
                table.replaceData(url);
            }
            $('body').on('keyup','#searchInput',function(){
                let value = $(this).val().toLowerCase();
                console.log(value);
                table.setFilter(function(data) {
                    return Object.values(data).some(field => 
                        field !== null && field !== undefined && field.toString().toLowerCase().includes(value)
                    );
                });
            });

            $('body').on('click','#clearSearch',function(){
                document.getElementById("searchInput").value = "";
                table.clearFilter();
            });

        });
		
	</script>
    
    	
