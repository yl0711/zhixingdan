<form id="reviewInfo_form" method="post" action="{{url('documents/reviewCancel')}}/{{$id}}" target="reviewInfo_iframe">
	<input type="hidden" name="id" value="{{$id}}" />
	<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr" width="30%"> 拒绝原因 ：</td>
			<td class="tl" style="padding-bottom: 10px;">
				<textarea id="intro" name="intro" cols="70" rows="5"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan = "2">
				<button type="submit" id="form_submit" class="btn btn-success" >提交</button>
			</td>
		</tr>
	</tbody>
</table>
</form>
<iframe style="width:0; height:0; display: none;" name="reviewInfo_iframe"></iframe>