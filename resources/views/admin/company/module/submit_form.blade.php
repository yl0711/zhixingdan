<table class ="prodict_edit">
	<tbody >
		<tr>
			<td class="tr"> 名称 :</td>
			<td class="tl">
				<input name="name" id="name" class="form-control" placeholder="名称" 
					@if ($page_type=='modify') value="{{$company['name']}}" @endif>
			@if ($page_type=='modify')
				<input id="id" type="hidden" value="{{$company['id']}}" />
				<input id="oldname" type="hidden" value="{{$company['name']}}" />
			@endif
			</td>
		</tr>
		<tr>
			<td class="tr"> 地址 :</td>
			<td class="tl">
				<input name="addr" id="addr" class="form-control" placeholder="地址" 
					@if ($page_type=='modify') value="{{$company['addr']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> 联系人 :</td>
			<td class="tl">
				<input name="person" id="person" class="form-control" placeholder="联系人" 
					@if ($page_type=='modify') value="{{$company['person']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> 联系电话-座机 :</td>
			<td class="tl">
				<input name="phone" id="phone" class="form-control" placeholder="座机" 
					@if ($page_type=='modify') value="{{$company['phone']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> 联系电话-手机 :</td>
			<td class="tl">
				<input name="mobile" id="mobile" class="form-control" placeholder="手机" 
					@if ($page_type=='modify') value="{{$company['mobile']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> Email :</td>
			<td class="tl">
				<input name="email" id="email" class="form-control" placeholder="电子邮件" 
					@if ($page_type=='modify') value="{{$company['email']}}" @endif>
			</td>
		</tr>
		<tr>
			<td class="tr"> 网站主页 :</td>
			<td class="tl">
				<input name="homepage" id="homepage" class="form-control" placeholder="网站主页" 
					@if ($page_type=='modify') value="{{$company['homepage']}}" @endif>
			</td>
		</tr>
		<tr>
			<th colspan = "2" >
				<button type="button" id="form_submit" class="btn btn-success" >确认提交</button>
			</th>
		</tr>
	</tbody>
</table>