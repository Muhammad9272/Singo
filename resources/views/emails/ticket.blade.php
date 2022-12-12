<!DOCTYPE html>
    <head>
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
                <div class="col-md-12 text-justify">
                    @if($reply != null)
                        {{ $reply }}
                    @else
                        Thank you for contacting our support team. A support ticket has now been opened for your request. You will be notified when a response is made by email. The details of your ticket are shown below.
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Ticket Details</div>
                        <div class="card-body">

                            <div class = "text-justify" >
                                <p>Ticket ID: {{ $ticket_details->id }}</p>
                                <p>Ticket Subject: {{ $ticket_details->subject}}</P>
                                <p>Ticket Status: {{ $ticket_details->getTicketStatus() }}</P>
                                <br>
                                <br>
                                <p>You can view the ticket at any time at https://app.singo.io/user/ticket/{{ $ticket_details->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
