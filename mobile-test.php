<!DOCTYPE html>
<html>
<head>
    <title>Mobile Compatibility Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .test-card { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }
        .pass { color: green; }
        .fail { color: red; }
        @media (max-width: 768px) {
            .test-card { padding: 15px; }
        }
    </style>
</head>
<body>
    <h1>📱 Mobile Compatibility Test</h1>
    
    <div class="test-card">
        <h2>Viewport Test</h2>
        <p>Screen Width: <span id="width"></span>px</p>
        <p>Screen Height: <span id="height"></span>px</p>
        <p>Pixel Ratio: <span id="pixelRatio"></span></p>
    </div>
    
    <div class="test-card">
        <h2>Touch Support</h2>
        <p id="touchTest">Testing...</p>
    </div>
    
    <div class="test-card">
        <h2>Orientation</h2>
        <p id="orientation"></p>
    </div>
    
    <div class="test-card">
        <h2>Recommended Actions</h2>
        <ul id="recommendations"></ul>
    </div>
    
    <script>
        // Display screen info
        document.getElementById('width').textContent = window.innerWidth;
        document.getElementById('height').textContent = window.innerHeight;
        document.getElementById('pixelRatio').textContent = window.devicePixelRatio;
        
        // Test touch support
        const touchTest = document.getElementById('touchTest');
        if ('ontouchstart' in window) {
            touchTest.innerHTML = '✅ Touch screen detected';
            touchTest.className = 'pass';
        } else {
            touchTest.innerHTML = '⚠️ No touch screen detected';
            touchTest.className = 'fail';
        }
        
        // Orientation
        const orientation = document.getElementById('orientation');
        function updateOrientation() {
            orientation.textContent = screen.orientation ? screen.orientation.type : 
                (window.innerHeight > window.innerWidth ? 'portrait' : 'landscape');
        }
        updateOrientation();
        window.addEventListener('orientationchange', updateOrientation);
        window.addEventListener('resize', updateOrientation);
        
        // Recommendations
        const recs = document.getElementById('recommendations');
        if (window.innerWidth <= 768) {
            recs.innerHTML += '<li>✅ Mobile view active</li>';
        } else {
            recs.innerHTML += '<li>💻 Desktop view active - resize window to test mobile</li>';
        }
        
        if (window.innerWidth <= 480) {
            recs.innerHTML += '<li>📱 Small mobile device detected</li>';
        } else if (window.innerWidth <= 768) {
            recs.innerHTML += '<li>📱 Tablet device detected</li>';
        }
        
        // Check if meta viewport is set correctly
        const viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            recs.innerHTML += '<li>✅ Viewport meta tag found</li>';
        } else {
            recs.innerHTML += '<li>❌ Viewport meta tag missing</li>';
        }
    </script>
</body>
</html>