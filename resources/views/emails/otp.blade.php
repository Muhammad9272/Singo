<!DOCTYPE html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row content-header mb-2">
            <div class="col-md-12 text-center">
                Welcome to <a href = "https://app.singo.io/">Singo.io</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">

                        <div class = "text-center" >
                            <p>Your OTP is: {{$msg}}</p>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
