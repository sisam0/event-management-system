<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="parent">
        <div class="nav-bar">
            <div class="nav-left">
                <img src="" alt="" height="50px" width="50px" class="admin.pic">
            </div>

            <div class="right">
                <a href="">User</a>
                <a href="">Reports</a>
                <a href="">Service</a>
                <a href="">Logout</a>
            </div>
        </div>

        <div class="contents">
            <div class="service-names">
                <span class="name">Hall</span>
                <span class="name">Catering</span>
            </div>

            <div id="render">
                <div class="hall">
                    <div id="addHall">
                        <i class="fa-solid fa-plus"></i> Add new hall
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="admin.js"></script>
</body>

</html>