<section class="content">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 mt-2 mb-2">
                                        <button type="button" class="btn btn-sm btn-info to-top hidden" value="<?php echo $top_id ?>"><i class="fa fa-arrow-up"></i> Top</button>
                                        <button type="button" class="btn btn-sm btn-info up-one-level hidden"><i class="fa fa-arrow-up"></i> Up One Level</button>
                                    </div>
                                    <div class="col-md-4 mt-2 mb-2">
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="username" class="form-control" placeholder="Enter Member ID">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-sm btn-primary btn-flat" id="search">Search Member</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-2 mb-2 text-danger" id="error-div">
                                        
                                    </div>
                                </div><hr>
                                <div class="row">
                                    <div class="col-md-12">                            
                                        <div class="table-responsive" id="tree-div">
                                            <?php echo $tree; ?>
                                        </div>
                                        <div id="tree-loader" class="text-center hidden"><img src="<?php echo file_url('assets/images/dotsloader.gif'); ?>" alt="" height="50"></div>
                                    </div>
                                </div><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            <input type="hidden" id="parent">
            <div id="tree-member-details" style="display:none;">
                <ul>
                    <li>Sponsor ID : <span id="ref"></span></li>
                    <li>Name : <span id="name"></span></li>
                    <li>Joining Date : <span id="jdate"></span></li>
                    <li>Activation Date : <span id="adate"></span></li>
                    <li>Package : <span id="package"></span></li>
                    <li class="hidden">Rank : <span id="rank"></span></li>
                    <li>Left Members : <span id="left"></span></li>
                    <li>Right Members : <span id="right"></span></li>
                </ul>
            </div>
        </div>
    </div>
</section>
    <script>
		$(document).ready(function(e) {  
			$('body').on('click','.open-branch',function(e){
				var targetName=e.target.nodeName;
				targetName=targetName.toLowerCase();
				if(targetName=='button'){
					var regid=$(this).val();
				}
				else if(targetName=='img'){
					var regid=$(this).siblings('button').val();
				}
				else if(targetName=='div'){
					var regid=$(this).find('button').val();
				}
				//console.log(regid);
				if(regid=='' || regid==undefined){ return false; }
				getTree(regid);
				$('#username').val('');
			});
			$('body').on('mouseover','.member-img', function (e) {
				$('#tree-member-details').addClass('view');
				docWidth=$(document).width();
				var top=$(this).offset().top;
				var left=$(this).offset().left;
				if(left>docWidth/2){
					left-=300;
				}
				else{
					left+=$(this).outerWidth();
				}
				//console.log(left)
				if(top>650){top-=100;}
				$(".view").css({
					top: top,
					left: left,
					position:'absolute'
				});
				var cursortop=e.pageY+20;
				var cursorleft=e.pageX+10;
				$('#cursor-loader').offset({
					top: top ,
					left: left
				});
				var regid=$(this).siblings('button').val();
				getDetails(regid);
			});
			$('body').on('mouseout','.member-img', function (e) {
				$('#tree-member-details').removeClass('view').hide(50);
			});
			$('body').on('click','.up-one-level',function(e){
				getTree($('#parent').val());
				$('#username').val('');
			});
			$('body').on('click','.to-top',function(e){
				getTree($(this).val());
				$('#username').val('');
			});
			$('#search').click(function(){
				var username='a'+$('#username').val();
				getTree(username);
			});
			$('body').on('click','.add-btn',function(e){
				var parent=$(this).data('parent');
				var position=$(this).val();
				window.location="<?php echo base_url('members/registration/'); ?>?parent="+parent+"&position="+position;
			});
        });
		
		function getTree(regid){
			$('.to-top,.up-one-level').addClass('hidden');
			$("#error-div").text("");
			$.ajax({
				type:"POST",
				url:"<?php echo base_url("members/gettree"); ?>",
				data:{regid:regid,club_id:'<?= $club_id ?>'},
				beforeSend: function(data){
					$('#tree-loader').removeClass('hidden');;
				},
				success: function(data){
					$('#tree-loader').addClass('hidden');
					if(data!='invalid'){
						$('#tree-div').html(data);
					}
					else{
						$("#error-div").html("<h4 class='m-0'>Enter valid ID from your Downline!</h4>");
					}
					var root=$('.to-top').val();
					var treeroot=$('.tree-btn').first().val();
					$('#parent').val($('.tree-btn').first().attr('data-parent'));
					if(root!=treeroot){
						$('.to-top,.up-one-level').removeClass('hidden');
					}
				}
			});
		}
		
		function getDetails(regid){
            return false;
			$.ajax({
				type:"POST",
				url:"<?php echo base_url('members/getpopupdetails'); ?>",
				data:{regid:regid},
				beforeSend: function(data){
					$('.member-img').css('cursor','progress');
				},
				success: function(data){
					//console.log(data);
					$('.member-img').css('cursor','auto');
					if(data!='false'){
						data=JSON.parse(data);
						$('#ref').text(data['ref']);
						$('#name').text(data['name']);
						$('#jdate').text(data['date']);
						$('#adate').text(data['activation_date']);
						$('#left').text(data['left']);
						$('#right').text(data['right']);
						$('#rank').text(data['rank']);
						$('#package').text(data['package']);
						$('.view').show();
					}
					
				},
				error: function(data){
					console.log(data);
				}
			});
		}
		
	</script>
    
    	
