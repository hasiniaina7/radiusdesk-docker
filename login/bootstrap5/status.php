<!DOCTYPE html>
<html id='htmlMain' class="h-100" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Dirk van der Walt">
    <meta name="generator" content="Gedit">
    <title>Tech Zone Hotspot | Status</title>
    
    <link rel="stylesheet" href="https://dev.techzone.lat/login/bootstrap5/css/bootstrap-icons.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://dev.techzone.lat/login/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://dev.techzone.lat/login/bootstrap5/css/bootstrap-icons.css" rel="stylesheet">
    <meta name="theme-color" content="#7952b3">
    
    <!-- Custom styles -->
    <link href="https://dev.techzone.lat/login/bootstrap5/css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="https://dev.techzone.lat/login/bootstrap5/css/style.css" rel="stylesheet">
    
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .login-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            margin-right: 2px;
        }
        .login-tabs .nav-link.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

<!-- The overlay -->
<div class="overlay">
    <span class="loader"></span>
</div>



<!-- Begin page content -->
<main class="flex-shrink-1 mt-5 pt-4">
    <div class="container mt-4">
        <!-- Login Modal Content (now as main content) -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">

                    
                    <!-- Login Tabs -->
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs login-tabs" id="loginTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-login" type="button" role="tab">
                                    <i class="bi-person"></i> User
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="voucher-tab" data-bs-toggle="tab" data-bs-target="#voucher-login" type="button" role="tab">
                                    <i class="bi-ticket"></i> Voucher
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-4" id="loginTabContent">
                            <!-- User Login Tab -->
                            <div class="tab-pane fade show active" id="user-login" role="tabpanel">
                                <form id="frmUserLogin">
                                    <div class="alert alert-info" id='alertInfo'>
                                        <div data-translate="sPlease_log_in_to_use_the_Wi-Fi_Internet_services_fs">
                                            Please log in to use the Wi-Fi Internet services.
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-warning collapse" id='alertWarn'>
                                        <div style="font-weight: bold"></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="txtUsername" class="form-label">Nom d'utilisateur</label>
                                        <input type="text" class="form-control" id="txtUsername" placeholder="Nom d'utilisateur" required>
                                        <div class="invalid-feedback">Veuillez saisir un nom d'utilisateur valide.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="txtPassword" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="txtPassword" placeholder="Mot de passe" required>
                                        <div class="invalid-feedback">Veuillez saisir un mot de passe.</div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg" id='btnUserConnect'>
                                            <i class='bi-check'></i> <span data-translate="sOk">OK</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Voucher Login Tab -->
                            <div class="tab-pane fade" id="voucher-login" role="tabpanel">
                                <form id="frmVoucherLogin">
                                    <div class="alert alert-info">
                                        <div data-translate="sEnter_voucher_code">
                                            Entrez votre code voucher pour vous connecter.
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="txtVoucher" class="form-label">Code Voucher</label>
                                        <input type="text" class="form-control" id="txtVoucher" placeholder="Code voucher" required>
                                        <div class="invalid-feedback">Veuillez saisir un code voucher valide.</div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg" id='btnVoucherConnect'>
                                            <i class='bi-check'></i> <span data-translate="sConnect">Se connecter</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Section (initially hidden) -->
        <div class="row mt-4 d-none" id="dashboardSection">
            <div class="col-12">
                <h2 class="text-center mb-4">Votre consommation</h2>
                
                <!-- Session + Usage -->
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content p-0" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-session">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th data-translate="sItem">Item</th>
                                            <th data-translate="sValue">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="bi-person"></i> <span data-translate="sUsername">Username</span></td>
                                            <td id="acct_un" style="color:#2759A2;">N/A</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi-clock"></i> <span data-translate="sConnected">Connected</span></td>
                                            <td id="acct_up" style="color:#2759A2;">N/A</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi-arrow-down"></i> <span data-translate="sData_In">Data In</span></td>
                                            <td id="acct_di" style="color:#2759A2;">N/A</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi-arrow-up"></i> <span data-translate="sData_Out">Data Out</span></td>
                                            <td id="acct_do" style="color:#2759A2;">N/A</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi-hdd"></i> <span data-translate="sData_Total">Data Total</span></td>
                                            <td id="acct_dt" style="color:#2759A2;">N/A</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-sm-12">
                                    <span data-translate="sRefreshing_in">Refreshing in</span>
                                    <span id="status_refresh" class="info" style="color:#2759A2;">N/A</span>
                                    <span data-translate="sseconds_fs">seconds.</span>
                                </div>
                                
                                <div class="d-grid gap-2 mt-3">
                                    <button type="button" class="btn btn-warning btn-lg" id='btnDisconnect'>
                                        <i class='bi-x'></i> <span data-translate="sDisconnect">Disconnect</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://dev.techzone.lat/login/bootstrap5/js/jquery.min.js"></script>
<script src="https://dev.techzone.lat/login/bootstrap5/js/js.cookie.js"></script>
<script src="https://dev.techzone.lat/login/bootstrap5//js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Cache DOM elements
    const $username = $('#acct_un');
    const $up = $('#acct_up');
    const $di = $('#acct_di');
    const $do_ = $('#acct_do');
    const $dt = $('#acct_dt');
    const $statusRefresh = $('#status_refresh');
    const $overlay = $('.overlay');
    const $loginSection = $('.container .row:first-child');
    const $dashboardSection = $('#dashboardSection');
    const $alertWarn = $('#alertWarn');

    let currentUser = localStorage.getItem('username') || '';
    let currentType = localStorage.getItem('user_type') || 'user';
    let refreshTimer;

    // Save user info to localStorage
    function saveUserInfo(username, userType) {
        localStorage.setItem('username', username);
        localStorage.setItem('user_type', userType);
        currentUser = username;
        currentType = userType;
    }

    // Clear user info from localStorage
    function clearUserInfo() {
        localStorage.removeItem('username');
        localStorage.removeItem('user_type');
        currentUser = '';
        currentType = 'user';
    }

    // Format bytes to human-readable format
    function humanBytes(bytes) {
        if (!bytes || bytes === 'N/A') return 'N/A';
        const units = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];
        let value = parseFloat(bytes);
        let i = 0;
        while (value >= 1000 && i < units.length - 1) {
            value /= 1000;
            i++;
        }
        return `${value.toFixed(2)} ${units[i]}`;
    }

    // Update dashboard display
    function updateDisplay(data) {
        $username.text(currentUser || 'N/A');
        $up.text('Connected');
        $di.text(humanBytes(data.totalIn) || 'N/A');
        $do_.text(humanBytes(data.totalOut) || 'N/A');
        $dt.text(humanBytes(data.totalInOut) || 'N/A');
    }

    // Update refresh countdown timer
    function updateRefreshTimer(secondsLeft = 30) {
        $statusRefresh.text(secondsLeft);
        if (secondsLeft <= 0) return;
        refreshTimer = setTimeout(() => updateRefreshTimer(secondsLeft - 1), 1000);
    }

    // Fetch data from API
    function fetchData() {
        if (!currentUser) return;
        $.ajax({
            url: 'https://dev.techzone.lat/cake4/rd_cake/radaccts.json',
            method: 'GET',
            data: {
                username: currentUser,
                from: '2025-07-01',
                to: '2025-07-31',
                limit: 1000,
                order: 'Radacct.acctstarttime desc',
                cloud_id: 23,
                token: 'b4c6ac81-8c7c-4802-b50a-0a6380555b50',
                page: 1,
                type: currentType
            },
            headers: {
                'Accept': 'application/json',
                'X-Api-Key': 'b4c6ac81-8c7c-4802-b50a-0a6380555b50'
            },
            success: function(data) {
                console.log('Data received:', data);
                updateDisplay(data);
                updateRefreshTimer(30);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                updateDisplay({
                    totalIn: 'Error',
                    totalOut: 'Error',
                    totalInOut: 'Error'
                });
                updateRefreshTimer(30);
            }
        });
    }

    // Initialize page based on login status
    function initializePage() {
        if (currentUser) {
            $loginSection.addClass('d-none');
            $dashboardSection.removeClass('d-none');
            updateDisplay({
                totalIn: 1024000,
                totalOut: 512000,
                totalInOut: 1536000
            });
            fetchData();
            setInterval(fetchData, 30000);
            updateRefreshTimer(30);
        } else {
            $loginSection.removeClass('d-none');
            $dashboardSection.addClass('d-none');
            $('#user-tab').tab('show');
        }
    }

    // User login form handler
    $('#frmUserLogin').on('submit', function(e) {
        e.preventDefault();
        const username = $('#txtUsername').val();
        const password = $('#txtPassword').val();

        if (!username || !password) {
            $alertWarn.removeClass('collapse').find('div').text('Veuillez remplir tous les champs.');
            return;
        }

        saveUserInfo(username, 'user');
        $overlay.show();

        setTimeout(() => {
            $loginSection.addClass('d-none');
            $dashboardSection.removeClass('d-none');
            $overlay.hide();
            fetchData();
            setInterval(fetchData, 30000);
            updateRefreshTimer(30);
        }, 2000);
    });

    // Voucher login form handler
    $('#frmVoucherLogin').on('submit', function(e) {
        e.preventDefault();
        const voucher = $('#txtVoucher').val();

        if (!voucher) {
            alert('Veuillez saisir un code voucher.');
            return;
        }

        saveUserInfo(voucher, 'voucher');
        $overlay.show();

        setTimeout(() => {
            $loginSection.addClass('d-none');
            $dashboardSection.removeClass('d-none');
            $overlay.hide();
            fetchData();
            setInterval(fetchData, 30000);
            updateRefreshTimer(30);
        }, 2000);
    });

    // Disconnect handler
    $('#btnDisconnect').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            $.ajax({
                url: 'https://login.techzone.lat/logout',
                method: 'POST'
            }).always(function() {
                clearUserInfo();
                $dashboardSection.addClass('d-none');
                $loginSection.removeClass('d-none');
                $('#frmUserLogin')[0].reset();
                $('#frmVoucherLogin')[0].reset();
                $alertWarn.addClass('collapse');
                $('#user-tab').tab('show');
                window.location = 'https://login.techzone.lat/login';
            });
        }
    });

    // Initialize page on load
    initializePage();
});
</script>

</body>
</html>
