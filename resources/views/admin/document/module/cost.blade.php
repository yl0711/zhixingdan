<form id="cost_form" method="post" enctype="multipart/form-data" action="{{url('documents/cost')}}" target="cost_iframe">
	<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> <span style="color: #FF0000;">*</span> 成本构成项 :</td>
			<td class="tl">
				<select id="cost_id" name="cost_id">
					<option value="0">请选择</option>
				@foreach($cost as $item)
					<option value="{{$item['id']}}">{{$item['name']}}</option>
				@endforeach
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr"> 预算 :</td>
			<td class="tl">
				<input name="money" id="money" class="form-control" placeholder="预算" >
			</td>
		</tr>
		<tr>
			<td class="tr"> 说明 :</td>
			<td class="tl">
				<input name="intro" id="intro" class="form-control" placeholder="说明" >
			</td>
		</tr>
		<tr>
			<td class="tr"> 上传附件 :</td>
			<td class="tl">
				<input type="file" name="attachment" />
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="submit" id="form_submit" class="btn btn-success" >确定</button>
			</th>
		</tr>
	</tbody>
</table>
</form>
<iframe style="width:0; height:0; display: none;" name="cost_iframe"></iframe>