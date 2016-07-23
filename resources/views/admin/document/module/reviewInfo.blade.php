<form id="reviewInfo_form" method="post" action="{{url('documents/review')}}" target="reviewInfo_iframe">
	<input type="hidden" name="id" value="{{$id}}" />
	<input type="hidden" name="doc_id" value="{{$doc_id}}" />
	<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 审批结果 :</td>
			<td class="tl">
			@if(2==$review_type)
				<input type="radio" id="review_type_1" name="review_type" value="2" checked="checked" />
				<label for="review_type_1">通过</label>
			@elseif(-2==$review_type)
				<input type="radio" id="review_type_0" name="review_type" value="-2" checked="checked" />
				<label for="review_type_0">拒绝</label>
			@else
				<input type="radio" id="review_type_1" name="review_type" value="2" />
				<label for="review_type_1">通过</label>
				<input type="radio" id="review_type_0" name="review_type" value="-2" />
				<label for="review_type_0">拒绝</label>
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> 审批原因 :</td>
			<td class="tl">
				<textarea id="reiew_intro" name="reiew_intro" cols="50" rows="3"></textarea>
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