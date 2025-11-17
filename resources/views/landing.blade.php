<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .landing-container {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }

        .logo {
            margin-bottom: 40px;
        }

        .logo img {
            max-width: 200px;
            height: auto;
        }

        .landing-title {
            color: #fff;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .landing-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 20px;
            font-weight: 400;
            margin-bottom: 60px;
        }

        .registration-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .registration-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .registration-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            color: #fff;
        }

        .card-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .card-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-register {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: #fff;
        }

        .btn-register i {
            margin-right: 8px;
        }

        .login-link {
            margin-top: 30px;
            color: rgba(255,255,255,0.9);
            font-size: 16px;
        }

        .login-link a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-bottom: 2px;
        }

        .login-link a:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .landing-title {
                font-size: 32px;
            }

            .landing-subtitle {
                font-size: 16px;
            }

            .registration-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .registration-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <div class="logo">
            <img src="{{ asset('frontend/logo/logo.svg') }}" alt="Logo">
        </div>

        <h1 class="landing-title">Tour Management System</h1>
        <p class="landing-subtitle">Manage your tours efficiently and connect with travelers</p>

        <div class="registration-cards">
            <div class="registration-card">
                <div class="card-icon">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h2 class="card-title">Travel Agency</h2>
                <p class="card-description">
                    Register your travel agency to create and manage tours, track expenses, and handle payments from members.
                </p>
                <a href="{{ route('travel.agency.show.register') }}" class="btn-register">
                    <i class="fa-solid fa-user-plus"></i> Register as Agency
                </a>
            </div>

            <div class="registration-card">
                <div class="card-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h2 class="card-title">Member</h2>
                <p class="card-description">
                    Join as a member to browse available tours, register for tours, and manage your tour history and payments.
                </p>
                <a href="{{ route('member.show.register') }}" class="btn-register">
                    <i class="fa-solid fa-user-plus"></i> Register as Member
                </a>
            </div>
        </div>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>

