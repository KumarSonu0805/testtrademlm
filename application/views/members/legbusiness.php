<style>
    .price-tooltip{
        position: absolute;
        font-size: 10px;
        margin-left: 5px;
        border: 1px solid;
        padding: 3px 6px;
        border-radius: 50%;
    }
    .tabulator-placeholder-contents{
        color: #C00B0E !important;
    }
    .select-leg{
        cursor: pointer;
    }
</style>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Leg 1 -->
                            <div class="col-md-6">
                                <div class="card card-stats card-primary card-round select-leg" data-value="leg-1">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <div class="icon-big text-center">
                                                    <i class="flaticon-diagram text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-9 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Leg 1 Business</p>
                                                    <h4 class="card-title"><?= isset($top_legs[0])?$this->amount->toDecimal($top_legs[0]['business'],false).' DXC':0; ?><i class="fa fa-info price-tooltip" data-toggle="tooltip" data-placement="top" title="<?= isset($top_legs[0])?$this->amount->toDecimal($top_legs[0]['business_usdt'],false).' USDT':0; ?>"></i></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Leg 2 -->
                            <div class="col-md-6">
                                <div class="card card-stats card-primary card-round select-leg" data-value="leg-2">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-3">
                                                <div class="icon-big text-center">
                                                    <i class="flaticon-diagram text-white"></i>
                                                </div>
                                            </div>
                                            <div class="col-9 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Leg 2 Business</p>
                                                    <h4 class="card-title"><?= isset($top_legs[1])?$this->amount->toDecimal($top_legs[1]['business'],false).' DXC':0; ?><i class="fa fa-info price-tooltip" data-toggle="tooltip" data-placement="top" title="<?= isset($top_legs[1])?$this->amount->toDecimal($top_legs[1]['business_usdt'],false).' USDT':0; ?>"></i></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-none">
                            <div class="col-6 col-sm-4 col-lg-3">
                                <div class="card card-info select-leg" data-value="leg-1">
                                    <div class="card-body p-3 text-center">
                                        <div class="h1 m-0"><?= isset($top_legs[0])?$this->amount->toDecimal($top_legs[0]['business'],false).' DXC':0; ?><i class="fa fa-info price-tooltip" data-toggle="tooltip" data-placement="top" title="<?= isset($top_legs[0])?$this->amount->toDecimal($top_legs[0]['business_usdt'],false).' USDT':0; ?>"></i></div>
                                        <div class="text- h3 mb-3">Leg 1</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3">
                                <div class="card card-info select-leg" data-value="leg-2">
                                    <div class="card-body p-3 text-center">
                                        <div class="h1 m-0"><?= isset($top_legs[1])?$this->amount->toDecimal($top_legs[1]['business'],false).' DXC':0; ?><i class="fa fa-info price-tooltip" data-toggle="tooltip" data-placement="top" title="<?= isset($top_legs[1])?$this->amount->toDecimal($top_legs[1]['business_usdt'],false).' USDT':0; ?>"></i></div>
                                        <div class="text- h3 mb-3">Leg 2</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">   
                                <div id="tabulator-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
    <script>
	
		$(document).ready(function(e) {

            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
            
            var url="<?= base_url('members/getlegbusiness/'); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        type: "auto"
                    },
                    { title: "Member ID", field: "username" },
                    { title: "Member Name", field: "name" },
                    { title: "Total Business (DXC)", field: "current_amount" },
                    { title: "Total Business (USDT)", field: "current_amount_usdt" },
                    //{ title: "Current Business (DXC)", field: "current_amount" },
                    //{ title: "Current Business (USDT)", field: "current_amount_usdt" }
                ];

            var pagination={
                sizes:[10, 20, 50, 100]
            }

            var options={
                placeholder: "Click on Leg to view Leg Business"
            }
            
            var table=createTabulator('tabulator-table',url,columns,pagination,'fitColumns',options);

            function refreshTableData(leg='') {
                let newUrl=url;
                if(leg!=''){
                    newUrl+='?leg='+leg
                }
                table.replaceData(newUrl);
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

            $('body').on('click','.select-leg',function(){
                $('.select-leg').removeClass('card-success').addClass('card-primary');
                $(this).removeClass('card-primary').addClass('card-success');
                var leg=$(this).data('value');
                refreshTableData(leg);
            });

        });
		
	</script>
    
    	
