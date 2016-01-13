<!DOCTYPE html>
<html>
    <head>
        <title>参数错误</title>

        

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">@if (isset($exception) && !empty($exception->getMessage()))
                        {{$exception->getMessage()}}
                    @else
                        Sorry..该资源没有找到！
                    @endif
                </div>
                <p>
                    <a href="#" onclick="history.go(-1);">点击返回</a>
                </p>
            </div>
        </div>
    </body>
</html>
