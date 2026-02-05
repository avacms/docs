<?php
/**
 * Home Template - Hero landing page
 * 
 * Uses shared header/footer partials with homepage-specific body class
 * for consistent navigation while allowing unique hero styling.
 */

// Homepage configuration
$showSidebar = false;
$showToc = false;
$bodyClass = 'home-hero-page';
?>
<?= $ava->partial('header', [
    'request' => $request,
    'item' => $content,
    'showSidebar' => $showSidebar,
    'bodyClass' => $bodyClass,
]) ?>

            <div class="dev-notice dev-notice-home">
                <div class="dev-notice-icon"></div>
                <div class="dev-notice-text">
                    <p><strong>This project is under very early, active development and may contain bugs or security issues. It is likely not ready for production websites.</strong></p>
                    <p>You are responsible for reviewing, testing, and securing any deployment. Ava CMS is provided as free, open-source software without warranty (GNU General Public License), see <a href="https://github.com/avacms/ava/blob/main/LICENSE" target="_blank" rel="noopener">LICENSE</a>.</p>
                </div>
            </div>

            <?= $ava->body($content) ?>

<?= $ava->partial('footer', ['showToc' => $showToc, 'isHomepage' => true]) ?>
