<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .property-header {

            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;

        }

        .property-header-content {
            display: flex;
            flex-direction: column;
            padding-left: 20px;
            position: relative;
            bottom: 32px;
            align-items: center;
        }

        .property-header-content h3 {
            color: #42424A;
            font-size: 24px;
            font-weight: 700;
            font-family: sans-serif;
            text-align: center
        }

        .property-header-content p {
            font-weight: 500
        }

        .property-header-content h4 {
            color: #FE8917;

        }

        .property-details,
        .statistics,
        .amenities {

            padding: 20px;
            border-radius: 10px;

            margin-bottom: 20px;
        }

        .property-details h5 , .statistics h5 {
            background: #42424A;
            padding: 10px;
            color: white;
            margin-bottom: 10px;
        }
        .amenities h5 {
            background: #FE8917;
            padding: 10px;
            color: white;
            margin-bottom: 10px;

        }

        .amenities .form-check {
            margin-bottom: 10px;
        }

        .amenities .form-check-label {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <!-- Property Header Section -->
        <div class="property-header row justify-content-between align-items-start">
            <div class="col-sm-6 col-lg-8">
                <img src="{{ asset('storage/images/view.jpg') }}" alt="Property Image" style="max-width: 100%"
                    class="mb-4" width="auto">
            </div>
            <div class="offset-sm-2 col-sm-4 col-lg-2">
                <div class="property-header-content">
                    <div style="height: 100%">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="img-fluid"
                            width="200px" height="77px">
                    </div>
                    <h3 class="mb-1" style="position: relative ; top : -10px"> {{$property->title}}</h3>
                    <p class="text-muted text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24px"
                            height="24px" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z"
                                stroke="#42424A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z"
                                stroke="#42424A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg> {{$property->location}}</p>
                    <h4 class=" text-center whitespace-nowrap">{{$property->price}} SAR</h4>
                </div>
            </div>
        </div>

        <!-- Property Details and Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="property-details">
                    <h5>Property Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between mb-3 " style="background: rgba(66, 66, 74, 0.17);">
                         <span>Bedrooms</span>
                         <span >{{$property->bedrooms}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                         <span>Bathrooms</span>
                         <span >{{$property->bathrooms}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                         <span>Type</span>
                         <span >{{$property->property_type}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                         <span>Space</span>
                         <span >{{$property->area}} m <sup>2</sup></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                         <span>Status</span>
                         <span >{{$property->status}}</span>
                        </li>

                    </ul>
                </div>
            </div>

        </div>

        <!-- Amenities Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="statistics">
                    <h5>Statistics</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                            <span>Actual Visits</span>
                            <span></span>

                           </li>
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                            <span>Visit requests</span>
                            <span>{{$property->getTotalVisitRequests()}}</span>

                           </li>
                        <li class="list-group-item d-flex justify-content-between mb-3" style="background: rgba(66, 66, 74, 0.17);">
                            <span>Search Times</span>
                            <span>{{$property->search_count}}</span>

                           </li>
                        {{-- <li class="list-group-item">Actual Visits: N/A</li>
                        <li class="list-group-item">Visit Requests: N/A</li>
                        <li class="list-group-item">Search Times: N/A</li> --}}
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="amenities">
                    <h5>Amenities</h5>

                 @foreach ($nearbyAmenities as $amenity )

                 
                 <div class="form-check d-flex justify-content-between p-0">
                     <label class="form-check-label" for="amenity">{{$amenity->name}}</label>
                     <input class="form-check-input" type="checkbox" id="amenity" checked  >
                 </div>
                   @endforeach






                        <!-- Add more amenities as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
