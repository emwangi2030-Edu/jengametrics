<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 2rem; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #212529; }
        .box { text-align: center; max-width: 420px; }
        h1 { font-size: 2.5rem; margin: 0 0 0.5rem; color: #212529; }
        p { margin: 0 0 1.5rem; font-size: 1.125rem; color: #6c757d; }
        a { display: inline-block; padding: 0.5rem 1.25rem; background: #2E8B57; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        a:hover { background: #268; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Something went wrong</h1>
        <p>We're sorry. Please try again later.</p>
        <a href="{{ url('/') }}">Go to home</a>
    </div>
</body>
</html>
