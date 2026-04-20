<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title ?? 'FlowmercePos - Terminal' }}</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed-variant": "#005236",
                        "surface": "#0f131f",
                        "surface-dim": "#0f131f",
                        "tertiary-fixed-dim": "#d0bcff",
                        "surface-container": "#1b1f2c",
                        "on-tertiary": "#3c0091",
                        "surface-container-highest": "#313442",
                        "on-tertiary-fixed-variant": "#5516be",
                        "secondary-fixed-dim": "#7bd0ff",
                        "inverse-surface": "#dfe2f3",
                        "error": "#ffb4ab",
                        "on-error": "#690005",
                        "on-surface-variant": "#bbcabf",
                        "surface-variant": "#313442",
                        "secondary": "#7bd0ff",
                        "background": "#0f131f",
                        "secondary-container": "#00a6e0",
                        "primary-fixed": "#6ffbbe",
                        "on-primary": "#003824",
                        "on-surface": "#dfe2f3",
                        "surface-tint": "#4edea3",
                        "tertiary": "#d0bcff",
                        "on-primary-fixed": "#002113",
                        "inverse-on-surface": "#2c303d",
                        "tertiary-container": "#b090ff",
                        "surface-container-high": "#262a37",
                        "on-secondary": "#00354a",
                        "surface-container-low": "#171b28",
                        "primary-fixed-dim": "#4edea3",
                        "on-tertiary-container": "#4600a7",
                        "on-secondary-fixed-variant": "#004c69",
                        "inverse-primary": "#006c49",
                        "on-error-container": "#ffdad6",
                        "outline-variant": "#3c4a42",
                        "outline": "#86948a",
                        "on-primary-container": "#00422b",
                        "on-secondary-fixed": "#001e2c",
                        "on-secondary-container": "#00374d",
                        "tertiary-fixed": "#e9ddff",
                        "primary": "#4edea3",
                        "on-tertiary-fixed": "#23005c",
                        "primary-container": "#10b981",
                        "surface-container-lowest": "#0a0e1a",
                        "error-container": "#93000a",
                        "secondary-fixed": "#c4e7ff",
                        "on-background": "#dfe2f3",
                        "surface-bright": "#353946"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Plus Jakarta Sans"],
                        "body": ["Plus Jakarta Sans"],
                        "label": ["Plus Jakarta Sans"],
                        "mono": ["JetBrains Mono"]
                    }
                },
            },
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(16px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #313442;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body min-h-screen overflow-hidden selection:bg-primary selection:text-on-primary">
    {{ $slot }}
</body>
</html>
