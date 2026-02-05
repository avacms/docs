<?php
/**
 * Page Template - Docs Theme
 * 
 * This template renders documentation pages with sidebar navigation.
 * 
 * Available variables:
 *   $content  - The page content item (Ava\Content\Item)
 *   $request  - The HTTP request object
 *   $route    - The matched route
 *   $ava      - Template helper
 *   $site     - Site configuration array
 * 
 * @see https://ava.addy.zone/docs/themes#templates
 */

// Only show sidebar on /docs pages
$showSidebar = str_starts_with($request->path(), '/docs');
$isHomepage = $request->path() === '/' || $request->path() === '';
$showToc = !$isHomepage;
$bodyClass = $isHomepage ? 'home-landing' : '';
?>
<?= $ava->partial('header', [
    'request' => $request,
    'item' => $content,
    'pageTitle' => $content->title() . ' | Ava CMS',
    'showSidebar' => $showSidebar,
    'bodyClass' => $bodyClass,
]) ?>

            <div class="docs-content-wrapper">
                <div class="docs-content">
                    <?php if (!$isHomepage): ?>
                    <div class="dev-notice">
                        <div class="dev-notice-icon"></div>
                        <div class="dev-notice-text">
                            <p><strong>This project is under very early, active development and may contain bugs or security issues. It is likely not ready for production websites.</strong></p>
                            <p>You are responsible for reviewing, testing, and securing any deployment. Ava CMS is provided as free, open-source software without warranty (GNU General Public License), see <a href="https://github.com/avacms/ava/blob/main/LICENSE" target="_blank" rel="noopener">LICENSE</a>.</p>
                        </div>
                    </div>
                    
                    <h1 class="page-title"><?= $ava->e($content->title()) ?></h1>
                    <?php endif; ?>
                    
                    <article class="markdown-section">
                        <?= $ava->body($content) ?>
                    </article>
                    
                    <?php if ($showSidebar): ?>
                    <?php
                        $issueTitle = urlencode('Docs feedback: ' . $content->title());
                        $pageUrl = 'https://ava.addy.zone' . $request->path();
                        $issueBody = urlencode("## Page\n{$pageUrl}\n\n## Feedback\n<!-- Describe the issue or suggestion -->\n\n");
                        $issueUrl = "https://github.com/avacms/docs/issues/new?title={$issueTitle}&body={$issueBody}&labels=documentation";
                    ?>
                    <div class="docs-feedback">
                        <a href="<?= $issueUrl ?>" target="_blank" rel="noopener" class="docs-feedback-link">
                            <svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"></path>
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"></path>
                            </svg>
                            Submit an issue or question about this page on GitHub
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($showToc): ?>
                <aside class="toc-sidebar" id="toc-sidebar">
                    <div class="toc-title">On This Page</div>
                    <ul class="toc-list" id="toc-list">
                        <!-- Populated by JavaScript -->
                    </ul>
                </aside>
                <?php endif; ?>
            </div>

<?= $ava->partial('footer', ['showToc' => $showToc]) ?>
