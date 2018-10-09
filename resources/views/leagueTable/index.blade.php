<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <?php /*<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>*/ ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script>
        jQuery(document).ready(function() {
            $('body').on('click', '#next', function () {
                $.ajax(
                    {
                        //url: "",
                        success: function(result){
                            //console.log(result);
                            $("#content").html(result);
                        }
                    }
                );
            });

            $('body').on('click', '#new', function () {
                $.ajax(
                    {
                        method : 'POST',
                        data: {newTournament : 1, _token: '{{csrf_token()}}'},
                        success: function(result){
                            //console.log(result);
                            $("#content").html(result);
                        }
                    }
                );
            });

            $('body').on('click', '#playAll', function () {
                $.ajax(
                    {
                        method : 'POST',
                        data: {playAll : 1, _token: '{{csrf_token()}}'},
                        success: function(result){
                            //console.log(result);
                            $("#content").html(result);
                        }
                    }
                );
            });

        });
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div id="content">
    @include('leagueTable.ajax')
</div>
</body>

</html>
