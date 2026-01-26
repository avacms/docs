---
title: Search
slug: search
status: published
meta_title: Search | Flat-file PHP CMS | Ava CMS
meta_description: Full-text search for Ava CMS. Configure search fields, weights, synonyms, stop words, and build search interfaces for your flat-file site.
excerpt: Ava CMS includes built-in full-text search with relevance scoring. Configure searchable fields, tune ranking weights, define synonyms and stop words, and build search pages for your theme.
---

Ava CMS includes built-in full-text search with relevance scoring—no external services required. Search is integrated into the content index, so queries are fast and results are ranked by relevance.

<div class="callout-info">
<strong>Quick Start:</strong> Use <code>$ava->query()->search('your query')->get()</code> in your theme to search content. Results are automatically scored and ranked.
</div>

## How Search Works

When you run [`./ava rebuild`](/docs/cli#rebuild), Ava CMS compiles search configuration (synonyms and stop words) into fast binary caches. On each search query:

1. **Tokenization** — The query is split into words and normalized (lowercased)
2. **Filtering** — Stop words are removed from the query tokens
3. **Expansion** — Remaining tokens are expanded with their synonyms
4. **Matching** — Content is checked for exact phrase matches and individual token matches
5. **Scoring** — Results are scored based on where matches occur (title vs body) and configurable weights
6. **Ranking** — Results are returned sorted by relevance score

Search queries load the full content index, so they're slightly slower than simple queries but still fast for most sites.

## Basic Usage

Add `->search()` to any query to filter and rank by search relevance:

```php
// Search all published content
$results = $ava->query()
    ->published()
    ->search('your query')
    ->get();

// Search a specific content type
$results = $ava->query()
    ->type('post')
    ->published()
    ->search('php tutorial')
    ->perPage(10)
    ->get();
```

Results are automatically sorted by relevance score. You can chain other query methods like `->type()`, `->perPage()`, and `->taxonomy()` with search.

## Configuring Searchable Content

### Per Content Type

Configure which fields are searchable and their weights in `app/config/content_types.php`:

```php
'post' => [
    'label'       => 'Posts',
    'content_dir' => 'posts',
    // ...other config
    
    'search' => [
        'enabled' => true,
        'fields'  => ['title', 'excerpt', 'body', 'author'],
        'weights' => [
            'title_phrase'     => 80,  // Exact phrase in title
            'title_all_tokens' => 40,  // All search words found in title
            'body_phrase'      => 20,  // Exact phrase in body
            'body_token'       => 2,   // Per-word match in body
            'featured'         => 15,  // Boost for featured items
        ],
    ],
],

'page' => [
    'label'       => 'Pages',
    'content_dir' => 'pages',
    // ...
    
    'search' => [
        'enabled' => true,
        'fields'  => ['title', 'body'],
    ],
],
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `enabled` | bool | `true` | Whether this content type appears in search results |
| `fields` | array | `['title', 'excerpt', 'body']` | Which fields to search |
| `weights` | array | See below | Scoring weights for different match types |

### Default Weights

If you don't specify weights, Ava CMS uses sensible defaults:

| Weight | Default | Description |
|--------|---------|-------------|
| `title_phrase` | 80 | Exact query phrase found in title |
| `title_all_tokens` | 40 | All search words found in title |
| `title_token` | 10 | Per-word match in title (max 30 total) |
| `excerpt_phrase` | 30 | Exact phrase in excerpt |
| `excerpt_token` | 3 | Per-word match in excerpt (max 15 total) |
| `body_phrase` | 20 | Exact phrase in body content |
| `body_token` | 2 | Per-word match in body (max 10 total) |
| `featured` | 15 | Bonus for items with `featured: true` |
| `field_weight` | 5 | Per-word match in custom fields |

Higher weights = higher ranking. A post with the exact phrase in its title will rank above one with scattered word matches in the body.

### Per-Query Weight Overrides

Override weights for specific queries without changing your config:

```php
$results = $ava->query()
    ->published()
    ->searchWeights([
        'title_phrase'     => 120,  // Boost title matches even more
        'title_all_tokens' => 60,   // Boost all-words-in-title bonus
        'body_phrase'      => 5,    // Downweight body matches
        'featured'         => 0,    // Ignore featured status
    ])
    ->search($query)
    ->get();
```

## Search Synonyms

Define groups of equivalent words so searching for any word in a group matches content containing other words in that group.

### Setup

Create `content/_search/synonyms.yml`:

```yaml
- [photo, image, picture, photograph]
- [quick, fast, rapid, speedy]
- [buy, purchase, acquire]
```

Run `./ava rebuild` to update the index.

### How Synonyms Work

- **Bidirectional** — Searching "photo" also matches "image", "picture", etc., and vice versa
- **Phrase matching unaffected** — Exact phrase searches only match literally
- **Cached** — Synonyms are compiled at rebuild time for fast lookup

### Synonym File Format

Each line is a YAML array of equivalent words:

```yaml
# Simple groups
- [photo, image, picture]
- [start, begin, commence]

# Words can appear in multiple groups
- [start, begin]
- [begin, commence, initiate]
```

Words are normalized to lowercase. Groups with fewer than 2 words are ignored.

<div class="callout-info">
<strong>Tip:</strong> Use synonyms for common variations in your content domain—product terminology, regional spelling differences, or abbreviations your audience might search for.
</div>

## Stop Words

Stop words are common words (like "the", "and", "is") that are filtered from search queries because they're too frequent to be useful for relevance.

### Setup

Create `content/_search/stopwords.yml`:

```yaml
# Stop Words
# Common words filtered from search queries.
# These words are too frequent to be useful for search relevance.

- a
- an
- the
- and
- or
- but
- is
- are
- was
- were
- be
- been
- being
- have
- has
- had
- do
- does
```

Run `./ava rebuild` to update the index.

### How Stop Words Work

- **Query filtering** — Stop words are removed from the token list used for individual word matching
- **Phrase matching unaffected** — The full search string (including stop words) is still matched as a phrase
- **Case insensitive** — Words are normalized to lowercase before comparison
- **Empty query handling** — If all tokens are stop words, no results are returned

### Default Behavior

If you don't create a `stopwords.yml` file, Ava CMS searches with all words. This is fine for most sites—stop word filtering primarily helps with very large content sets where common words would otherwise dominate results.

<div class="callout-info">
<strong>Tip:</strong> Start without stop words. If you notice search results being diluted by common terms, add a stop words file with the most frequent offenders.
</div>

## Building a Search Page

### Theme Route

Register a search route in your theme's `theme.php`:

```php
<?php
// app/themes/yourtheme/theme.php

use Ava\Application;

return function (Application $app): void {
    $app->router()->addRoute('/search', function ($request) use ($app) {
        $q = trim($request->query('q', ''));
        $results = [];
        
        if (strlen($q) >= 2) {
            $results = $app->query()
                ->published()
                ->search($q)
                ->perPage(20)
                ->get();
        }
        
        return $app->render('search', [
            'query'   => $q,
            'results' => $results,
        ]);
    });
};
```

### Search Template

Create `templates/search.php` in your theme:

```php
<?= $ava->partial('header') ?>

<h1>Search</h1>

<form action="/search" method="get">
    <input type="search" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php if ($query): ?>
    <?php if (empty($results)): ?>
        <p>No results found for "<?= htmlspecialchars($query) ?>"</p>
    <?php else: ?>
        <p>Found <?= count($results) ?> result(s) for "<?= htmlspecialchars($query) ?>"</p>
        
        <?php foreach ($results as $item): ?>
            <article>
                <h2><a href="<?= $item->url() ?>"><?= $item->title() ?></a></h2>
                <?php if ($item->excerpt()): ?>
                    <p><?= $item->excerpt() ?></p>
                <?php endif; ?>
                <small><?= $item->type() ?></small>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>

<?= $ava->partial('footer') ?>
```

### Search Form Anywhere

Add a search form to your header or sidebar:

```html
<form action="/search" method="get">
    <input type="search" name="q" placeholder="Search..." required minlength="2">
    <button type="submit">Search</button>
</form>
```

## JSON API Endpoint

Build an API endpoint for AJAX search or external integrations:

```php
$app->router()->addRoute('/api/search', function ($request) use ($app) {
    $query = trim($request->query('q', ''));
    
    if (strlen($query) < 2) {
        return \Ava\Http\Response::json([
            'results' => [],
            'message' => 'Query too short (minimum 2 characters)',
            'count'   => 0,
        ]);
    }
    
    $searchQuery = $app->query()
        ->type('post')
        ->published()
        ->search($query)
        ->perPage(20);
    
    $results = $searchQuery->get();
    
    return \Ava\Http\Response::json([
        'query'   => $query,
        'count'   => $searchQuery->count(),
        'results' => array_map(fn($item) => [
            'type'    => $item->type(),
            'title'   => $item->title(),
            'slug'    => $item->slug(),
            'url'     => $item->url(),
            'excerpt' => $item->excerpt(),
            'date'    => $item->date()?->format('Y-m-d'),
        ], $results),
        'pagination' => $searchQuery->pagination(),
    ]);
});
```

For cross-origin access (JavaScript from a different domain), add CORS headers. See [API Routes - CORS](/docs/api#cors-for-cross-origin-requests).

## Working Examples

- **Default theme** (server-rendered): [theme.php](https://github.com/avacms/ava/blob/main/app/themes/default/theme.php) and [search.php template](https://github.com/avacms/ava/blob/main/app/themes/default/templates/search.php)
- **Docs theme** (AJAX popup): [theme.php](https://github.com/avacms/docs/blob/main/app/themes/docs/theme.php) — registers `/search` and `/search.json` routes; front-end JS renders results in a modal

## Performance Notes

Search queries load the full content index (Tier 3 in the [caching strategy](/docs/performance#tiered-caching-strategy)), so they're slightly slower than simple queries. For most sites this is imperceptible, but on very large sites (10k+ items):

- Consider the [SQLite backend](/docs/performance#sqlite-backend) for lower memory usage
- Use `->perPage()` to limit results
- Cache search results if the same queries repeat frequently

<div class="related-docs">
<h2>Related Documentation</h2>
<ul>
<li><a href="/docs/configuration#content-content-types-content_typesphp">Configuration: Content Types</a> — Full content type options including search settings</li>
<li><a href="/docs/theming#search">Theming: Search</a> — Search in template context</li>
<li><a href="/docs/api#search-endpoint">API: Search Endpoint</a> — Building JSON search APIs</li>
<li><a href="/docs/performance">Performance</a> — Content indexing and caching strategies</li>
<li><a href="/docs/cli#rebuild">CLI: rebuild</a> — Rebuilding the search index</li>
</ul>
</div>
