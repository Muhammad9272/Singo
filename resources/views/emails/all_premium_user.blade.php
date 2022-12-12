



<!DOCTYPE html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
            integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
            crossorigin="anonymous"/>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        
    </head>

    <body>
        <div class="container-fluid">
            <div class="row content-header mb-2">
                <div class="col-md-12 text-center">
                    Welcome to <a href = "https://app.singo.io/">Singo.io </a> {{$user_details->name}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">

                            <div class = "text-center" >
                                <p>{{$msg}}</p>
                                
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </body>

</html>
