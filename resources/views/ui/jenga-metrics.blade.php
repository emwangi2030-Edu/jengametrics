<!DOCTYPE html>
<html lang="en">
<head>
    @php($hasViteManifest = file_exists(public_path('build/manifest.json')))
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JengaMetrics UI Preview</title>
    @if($hasViteManifest)
        @vite(['resources/js/jenga-metrics.jsx'])
    @endif
</head>
<body class="jm-theme jm-body-reset">
    @if($hasViteManifest)
        <div id="jenga-metrics-root"></div>
    @else
        <div style="max-width: 720px; margin: 2rem auto; font-family: Arial, sans-serif;">
            <div style="padding: 1rem 1.25rem; border: 1px solid #ffe69c; background: #fff3cd; border-radius: 0.5rem; color: #664d03;">
                UI preview assets are not built yet. Run <code>npm install</code> and <code>npm run build</code> to generate <code>public/build/manifest.json</code>.
            </div>
        </div>
    @endif
</body>
</html>
