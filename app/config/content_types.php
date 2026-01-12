<?php

declare(strict_types=1);

/**
 * ══════════════════════════════════════════════════════════════════════════════
 * AVA CMS — CONTENT TYPES
 * ══════════════════════════════════════════════════════════════════════════════
 *
 * Define the kinds of content your site has. Each content type specifies
 * where files live, how URLs are generated, and which templates to use.
 *
 * Docs: https://ava.addy.zone/docs/configuration#content-types-content_typesphp
 */

return [

    /*
    |───────────────────────────────────────────────────────────────────────────
    | PAGES
    |───────────────────────────────────────────────────────────────────────────
    | Static pages with hierarchical URLs that mirror the folder structure.
    |
    | content/pages/about.md       → /about
    | content/pages/about/team.md  → /about/team
    */

    'page' => [
        'label'       => 'Pages',
        'content_dir' => 'pages',

        'url' => [
            'type' => 'hierarchical',       // URL mirrors file path
            'base' => '/',
        ],

        'templates' => [
            'single' => 'page.php',
        ],

        'taxonomies' => [],                 // Pages typically don't use taxonomies
        'fields'     => [],                 // Custom fields (for validation/admin)
        'sorting'    => 'manual',           // manual, date_desc, date_asc, title

        'search' => [
            'enabled' => true,
            'fields'  => ['title', 'body'],
            'weights' => [                    // Optional: customise scoring
                'title_phrase' => 1000,         // Exact phrase in title
                'title_all_tokens' => 100,     // All search words in title
                'body_phrase' => 20,          // Exact phrase in body
                'body_token' => 5,            // Per-word match in body (max 10)
            ],
        ],
    ],


    /*
    |═══════════════════════════════════════════════════════════════════════════
    | ADD YOUR CONTENT TYPES BELOW
    |═══════════════════════════════════════════════════════════════════════════
    | Copy the structure above to create new content types.
    |
    | Example — Documentation:
    |
    |   'doc' => [
    |       'label'       => 'Documentation',
    |       'content_dir' => 'docs',
    |       'url' => [
    |           'type'    => 'pattern',
    |           'pattern' => '/docs/{slug}',
    |           'archive' => '/docs',
    |       ],
    |       'templates' => [
    |           'single'  => 'doc.php',
    |           'archive' => 'docs-archive.php',
    |       ],
    |       'taxonomies' => [],
    |       'sorting'    => 'manual',
    |   ],
    |
    | URL Pattern Placeholders:
    |   {slug} — Item slug         → /blog/my-post
    |   {id}   — Unique ID         → /posts/01HXYZ
    |   {yyyy} — 4-digit year      → /blog/2024/my-post
    |   {mm}   — 2-digit month     → /blog/2024/03/my-post
    |   {dd}   — 2-digit day       → /blog/2024/03/15/my-post
    */

];
