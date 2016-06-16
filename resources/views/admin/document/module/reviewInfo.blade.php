<form id="cost_form" method="post" action="{{url('documents/review')}}" target="cost_iframe">
	<input type="hidden" name="id" value="{{$id}}" />
	<input type="hidden" name="doc_id" value="{{$doc_id}}" />
	<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 审批结果 :</td>
			<td class="tl">
				<input type="radio" id="review_type_1" name="review_type" value="1" />
				<label for="review_type_1">通过</label>
				<input type="radio" id="review_type_0" name="review_type" value="0" />
				<label for="review_type_0">拒绝</label>
			</td>
		</tr>
		<tr>
			<td class="tr"> 审批原因 :</td>
			<td class="tl">
				<textarea id="reiew_intro" cols="" rows=""></textarea>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="submit" id="form_submit" class="btn btn-success" >提交</button>
			</th>
		</tr>
	</tbody>
</table>
</form>
<iframe style="width:0; height:0; display: none;" name="reviewInfo_iframe"></iframe>