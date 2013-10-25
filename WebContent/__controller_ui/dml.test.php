<script>
<?php
include_once( '../_lib/start.php');
include_once( __MODEL__. 'test.php');

$ictl  = new Controller();

( isset( $_POST['search_key'])) ? $search_key = $_POST['search_key'] : $search_key = null;

$iTest = new test();

$info = $iTest->get( $search_key);

echo $ictl->vars2input( array( "search_key"=>$search_key));
echo $ictl->makeTable ( "list", "f_idx", $info);
?>
$(function(){
	$("#btn_add").click(function(){
		var tmp;
		if(tmp = prompt("key,value")){
			tmp = tmp.split(',');
			var key = tmp[0];
			var val = tmp[1];
			$.post(
				"./<?=__URL_CTL_BACKEND__?>add.test.php",
				{
					"f_key" : encodeURI(key),
					"f_val" : encodeURI(val)
				}
			)
			.done(function(data){
				location.reload();
			})
			.fail(function(){
				alert("network error");
			});
		}
	});

	$("#btn_mod").click(function(){
		var tmp;
		if(idx = prompt("idx")){
			if(typeof $("tr[name=idx][value="+idx+"]")[0] != "undefined"){

				init_str
					= $("tr[name=idx][value="+idx+"]").children("td[name=f_key]").text()
					+ ","
					+ $("tr[name=idx][value="+idx+"]").children("td[name=f_val]").text();

				if(tmp = prompt("idx : " + idx + "\n\n" + "key,value" , init_str)){
					tmp = tmp.split(',');
					var key = tmp[0];
					var val = tmp[1];
					$.post(
						"./<?=__URL_CTL_BACKEND__?>mod.test.php",
						{
							"f_idx" : encodeURI(idx),
							"f_key" : encodeURI(key),
							"f_val" : encodeURI(val)
						}
					)
					.done(function(data){
						location.reload();
					})
					.fail(function(){
						alert("network error");
					});
				}
			} else {
				alert("idx out of range");
			}
		}
	});

	$("#btn_del").click(function(){
		var tmp;
		if(tmp = prompt("idx")){
			var idx = tmp;
			$.post(
				"./<?=__URL_CTL_BACKEND__?>del.test.php",
				{
					"f_idx" : encodeURI(idx)
				}
			)
			.done(function(data){
				location.reload();
			})
			.fail(function(){
				alert("network error");
			});
		}
	});

	$("#search_key").keyup(function(){
		$.post(
			"./<?=__URL_CTL_BACKEND__?>dml.test.key.php",
			{
				"f_key" : encodeURI($(this).val())
			}
		)
		.done(function(data){
			if(data == "ok"){
				$("#search_msg").text("KEY가 존재합니다.");
			}else{
				$("#search_msg").text("KEY가 존재하지 않습니다.");
			}
		})
		.fail(function(){

		});
	});
});
</script>
