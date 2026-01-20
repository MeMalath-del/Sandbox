@props(['title' => 'تسجيل الدخول'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - AutoParts Hub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
        }
        
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        
        .auth-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .btn-auth {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        
        .social-login {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e5e7eb;
            color: #4b5563;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .social-btn:hover {
            background-color: #f3f4f6;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #9ca3af;
            margin: 1.5rem 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e5e7eb;
        }
        
        .user-type-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type-card:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .user-type-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .user-type-card i {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
