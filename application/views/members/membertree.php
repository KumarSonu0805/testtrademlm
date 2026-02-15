
<style>
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
                                                <button type="button" class="btn btn-sm btn-success" id="search">Search</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <figure class="highcharts-figure">
                                                    <div id="member-tree"></div>
                                                </figure>
                                            </div>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <script>

                    $(document).ready(function(e) {
                        $('body').on('click','#search',function(){
                            var username=$('#username').val();
                            if(username!=''){
                                $.ajax({
                                    type:"post",
                                    url:'<?= base_url('members/gettreedata'); ?>',
                                    data:{username:username},
                                    success:function(data){
                                        var treedata=JSON.parse(data);	
                                        data=treedata['data'];
                                        var nodes=treedata['nodes'];
                                        treedata='<pre>'+JSON.stringify(treedata, null, 4)+'</pre>'
                                        //$('#member-tree').html(treedata);
                                        createTree(data,'',nodes)
                                    }
                                });
                            }
                        });
                    });
                    
                    function createTree(data,levels,nodes){
                        
                        Highcharts.chart('member-tree', {
                          chart: {
                            height: 900,
                            inverted: true
                          },
                          title: {
                            text: 'Member Tree'
                          },
                          accessibility: {
                            point: {
                              descriptionFormatter: function (point) {
                                var nodeName = point.toNode.name,
                                  nodeId = point.toNode.id,
                                  nodeDesc = nodeName === nodeId ? nodeName : nodeName + ', ' + nodeId,
                                  parentDesc = point.fromNode.id;
                                return point.index + '. ' + nodeDesc + ', reports to ' + parentDesc + '.';
                              }
                            }
                          },

                          series: [{
                            type: 'organization',
                            name: 'Member Tree',
                            keys: ['from', 'to'],
                            data: data,
                            nodes: nodes,
                            colorByPoint: false,
                            color: '#007ad0',
                            dataLabels: {
                              color: 'white'
                            },
                            borderColor: 'white',
                            nodeWidth: 40
                          }],
                          tooltip: {
                            outside: true
                          },
                          exporting: {
                              enabled:true,
                              buttons: {
                                  contextButton: {
                                      menuItems: ['viewFullscreen']
                                  }
                              }
                          }
                        });
                    }
                    
                    function validate(){
                        if(!confirm("Confirm Activate this Member?")){
                            return false;
                        }
                    }
                </script>
    
