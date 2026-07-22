<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Offered</title>
    <link rel="stylesheet" href="service.css">
</head>

<body>
    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
        </div>
        <!-- here is display flex -->
        <div class="contents">
            <div class="service-category">
                <button class="name-ser" onclick="showSection('service')">Services</button>
                <button class="name-ser" onclick="showSection('package')">Packages</button>
            </div>
            <div class="details">
                <div id="service" class="section">
                    <div class="top">
                        <span class="each-service">Hall</span>
                        <span class="each-service">Catering</span>
                    </div>
                    <div class="service-info">
                        <div class="card">
                            <img src="hall1.jpg" alt="" height="300px" width="440px">
                            <h3>Sagarmatha</h3>
                            <p>Seat Capacity: 450</p>
                            <p>Type: Indoor</p>
                        </div>
                        <div class="card">
                            <img src="hall1.jpg" alt="" height="300px">
                            <h3>Sagarmatha</h3>
                            <p>Seat Capacity: 450</p>
                            <p>Type: Indoor</p>
                        </div>
                        <div class="card">
                            <img src="hall1.jpg" alt="" height="300px">
                            <h3>Sagarmatha</h3>
                            <p>Seat Capacity: 450</p>
                            <p>Type: Indoor</p>
                        </div>
                        <div class="card">
                            <img src="hall1.jpg" alt="" height="300px">
                            <h3>Sagarmatha</h3>
                            <p>Seat Capacity: 450</p>
                            <p>Type: Indoor</p>
                        </div>
                    </div>
                </div>

                <div id="package" class="section">
                    <div class="service-info">
                        <div class="card">
                            <img src="venue.jpg" alt="" height="300px" width="440px">
                            <h3>Golden Package</h3>
                            <p>Includes Sagarmatha hall and menu for 600 guests!</p>
                        </div>
                        <div class="card">
                            <img src="venue.jpg" alt="" height="300px" width="440px">
                            <h3>Golden Package</h3>
                            <p>Includes Sagarmatha hall and menu for 600 guests!</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function showSection(id) {
            document.querySelectorAll(".section").forEach(section => {
                section.classList.remove("active");
            });

            document.getElementById(id).classList.add("active");

        }
        showSection("service");
    </script>
    
</body>

</html>