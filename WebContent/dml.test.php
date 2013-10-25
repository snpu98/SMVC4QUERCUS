<?php include_once( "./_lib/start.php"); ?>
<?php include_once( "./inc.head.php"); ?>



<?php include_once( "./inc.menu.php"); ?>


<!-- CONTENTS { -->
<div id="CONTENTS" class="LAYOUT">
	<form name="search_form" method="post" action="./dml.test.php">
		<table id="search">
			<tr>
				<td>
					<label for="search_key">KEY</label>
					<input type="text" name="search_key" id="search_key">
					<input type="submit" value="검색">
					<span id="search_msg">KEY를 입력하세요</span>
				</td>
			</tr>
		</table>
	</form>
	<table id="list">
		<tr name="idx" value="">
			<td name="f_idx"></td>
			<td name="f_key"></td>
			<td name="f_val"></td>
		</tr>
		<tr name="noRows">
			<td colspan="3" style="display:none;">데이터가 없습니다.</td>
		</tr>
	</table>
	<input type="button" value="추가" id="btn_add">
	<input type="button" value="편집" id="btn_mod">
	<input type="button" value="삭제" id="btn_del">
</div>
<!-- } CONTENTS -->

<?php include_once( "./__controller_ui/dml.test.php"); ?>

<?php include_once( "./inc.foot.php"); ?>