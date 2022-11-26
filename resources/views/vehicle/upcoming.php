<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
        jQuery(document).ready(function($){
            $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            });
        })
        </script>
        <style>
            body {
        overflow-x: hidden;
        }

        #sidebar-wrapper {
        min-height: 100vh;
        margin-left: -15rem;
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
        }

        #sidebar-wrapper .sidebar-heading {
        padding: 0.875rem 1.25rem;
        font-size: 1.2rem;
        }

        #sidebar-wrapper .list-group {
        width: 15rem;
        }

        #page-content-wrapper {
        min-width: 100vw;
        }

        #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
        }

        @media (min-width: 768px) {
        #sidebar-wrapper {
            margin-left: 0;
        }

        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: -15rem;
        }
        }
        </style>    
    </head>
    <body>
        <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Library management </div>
        <div class="list-group list-group-flush">
            <a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action bg-light">Dashboard</a>
            <a href="{{ route('index') }}" class="list-group-item list-group-item-action bg-light">Vehicle</a>

        </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

        </nav>

        <div class="container-fluid">
         <div class="container">
         
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3>Parking Slot management</h3>
               </div>
               <div class="panel-body">
                  <table class="table table-bordered table-hover">
                     <thead>
                        <a href="{{ route('create') }}" class="btn btn-xs btn-info pull-right"> <i class='fas fa-book-open'></i>  Add Student</a>
                        <br>
                        <tr>
                           <th>ID</th>
                           <th>Customer Name</th>
                           <th>VehIcle Number</th>
                           <th>Start Date</th>
                           <th>End Date</th>
                           <th>Driving Licence</th>
                           <th>Parking Fees</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($vehicles as $key => $details) 
                        
                        <tr>
                           <td>
                              {{ $vehicles['customer_name']}} 
                           </td>
                           <td>
                              {{ $vehicles['vehicle_number']}}
                           </td>
                           <td>
                              {{ $vehicles['start_date']}}
                           </td>
                           <td>
                              {{ $vehicles['end_date']}}
                           </td>
                           <td>
                             
                              <img src="{{ url('public/Image/'.$vehicles['driving_licence']) }}" style="height: 50px; width: 100px;">
                           </td>
                           <td>
                              {{ $vehicles['parking_fees']}}
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>

               </div>
            </div>
         </div>
      </div>
        </div>
        <!-- /#page-content-wrapper -->

        </div>
        <!-- /#wrapper -->
    </body>
</html>