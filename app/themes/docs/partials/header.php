<?php
/**
 * Header Partial - Docs Theme
 * 
 * This partial contains the document head and topbar for the documentation theme.
 * 
 * Available variables:
 *   $request      - The current HTTP request object
 *   $pageTitle    - Custom page title (optional)
 *   $item         - Content item for meta tags (optional)
 *   $showSidebar  - Whether to show the sidebar (optional, default: true)
 *   $ava          - The template helper
 *   $site         - Site configuration array
 * 
 * @see https://ava.addy.zone/docs/themes#partials
 */

$isDocsPage = str_starts_with($request->path(), '/docs');
$showSidebar = true;
$bodyClass = trim(($bodyClass ?? '') . ($isDocsPage ? '' : ' hide-sidebar-desktop'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/png" href="https://ava.addy.zone/media/icon.png">
    <link rel="apple-touch-icon" href="https://ava.addy.zone/media/icon.png">
    
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?= $ava->e($site['name']) ?>">
    <meta property="og:site_name" content="<?= $ava->e($site['name']) ?>">
    <meta property="og:locale" content="en_US">
    <meta name="twitter:card" content="summary">
    
    <?php if (isset($item)): ?>
        <?= $ava->metaTags($item) ?>
        <?= $ava->itemAssets($item) ?>
    <?php else: ?>
        <?php
        $title = $pageTitle ?? $site['name'];
        $description = $pageDescription ?? 'Documentation for Ava CMS - A developer-first flat-file PHP CMS';
        ?>
        <title><?= $ava->e($title) ?></title>
        <meta name="description" content="<?= $ava->e($description) ?>">
        <meta property="og:title" content="<?= $ava->e($title) ?>">
        <meta property="og:description" content="<?= $ava->e($description) ?>">
        <meta property="og:type" content="website">
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?= $ava->asset('fonts.css') ?>">
    <link rel="stylesheet" href="<?= $ava->asset('style.css') ?>">
    <script src="<?= $ava->asset('theme-init.js') ?>"></script>
</head>
<body class="<?= $ava->e($bodyClass) ?>">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="topbar-left">
                <a href="/" class="topbar-logo">Ava CMS<span class="topbar-version">v<?= AVA_VERSION ?></span></a>
                <nav class="topbar-nav">
                    <a href="/docs"<?= str_starts_with($request->path(), '/docs') ? ' class="active"' : '' ?>>Docs</a>
                    <a href="/themes"<?= $request->path() === '/themes' ? ' class="active"' : '' ?>>Themes</a>
                    <a href="/plugins"<?= $request->path() === '/plugins' ? ' class="active"' : '' ?>>Plugins</a>
                    <a href="/showcase"<?= $request->path() === '/showcase' ? ' class="active"' : '' ?>>Showcase</a>
                    <a href="https://github.com/AvaCMS/ava/releases" target="_blank" rel="noopener" class="external-link">Download <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:4px; opacity:0.7;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></a>
                </nav>
            </div>
            <div class="topbar-right">
                <div class="topbar-social">
                    <a href="https://github.com/orgs/avacms/discussions" target="_blank" rel="noopener" aria-label="Discussions">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 12V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v14l4-4h10a1 1 0 0 0 1-1m4-6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7a1 1 0 0 0-1-1z"/></svg>
                    </a>
                    <a href="https://github.com/avacms/ava" target="_blank" rel="noopener" aria-label="GitHub">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                    </a>
                </div>
                <button class="search-button" id="search-button" aria-label="Search" title="Search (Cmd+K)">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <kbd>⌘K</kbd>
                </button>
                <button class="theme-toggle" aria-label="Toggle dark mode">
                    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                </button>
                <button class="sidebar-toggle" aria-label="Toggle menu">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    
    <div class="docs-layout">
        <div class="sidebar-backdrop"></div>
        <?= $ava->partial('sidebar', ['request' => $request]) ?>
        
        <main class="docs-main">
