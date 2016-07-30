<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
</head>
<body>
<pre>
    {{$name}} 您好!

        您有一封待审核的执行单, 请尽快处理。

        项目名称: {{$project_name}}
        项目金额: {{$money}}
        成本预算: {{$cost_num}}
        成本占比: {{$cost_money}}

        <a href="{{url('documents/process')}}/{{$doc_id}}" target="_blank">点此链接进入审批页面</a>
</pre>
</body>
</html>