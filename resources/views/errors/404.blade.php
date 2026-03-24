<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page not found</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 2rem; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #212529; }
        .box { text-align: center; max-width: 420px; }
        h1 { font-size: 4rem; margin: 0 0 0.5rem; color: #2E8B57; }
        p { margin: 0 0 1.5rem; font-size: 1.125rem; color: #6c757d; }
        a { display: inline-block; padding: 0.5rem 1.25rem; background: #2E8B57; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        a:hover { background: #268; }
    </style>
</head>
<body>
    <div class="box">
        <h1>404</h1>
        <p>Page not found. The link may be broken or the page has been removed.</p>
        <a href="{{ url('/') }}">Go to home</a>
    </div>
</body>
</html>
