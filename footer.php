    </div><!-- /.container #main -->
</div><!-- /#body -->

<!-- 返回顶部按钮 -->
<a title="<?php esc_attr_e( '返回顶部', 'moe' ); ?>" id="top" href="#" onclick="scrollToTop(); return false;">
    <i class="fa fa-arrow-circle-o-up"></i>
</a>

<!-- 回到评论区按钮 -->
<?php if ( is_single() && ( comments_open() || get_comments_number() ) ) : ?>
<a title="<?php esc_attr_e( '查看评论', 'moe' ); ?>" id="goto-comments" class="goto-comments" href="#comments">
    <i class="fa fa-comments-o"></i>
</a>
<style>
/* 回到评论区按钮 */
#goto-comments {
    position: fixed;
    right: 20px;
    bottom: 90px;
    width: 50px;
    height: 50px;
    background: #FF6B9D;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 9998;
    box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

#goto-comments.show {
    opacity: 1;
    visibility: visible;
}

#goto-comments:hover {
    background: #FF4A82;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
}

#goto-comments i {
    color: #fff;
}

/* 移动端优化 */
@media screen and (max-width: 768px) {
    #goto-comments {
        width: 45px;
        height: 45px;
        right: 15px;
        bottom: 80px;
        font-size: 18px;
    }
}

@media screen and (max-width: 480px) {
    #goto-comments {
        width: 40px;
        height: 40px;
        right: 10px;
        bottom: 70px;
        font-size: 16px;
    }
}
</style>
<script>
// 控制回到评论区按钮显示/隐藏
(function() {
    var gotoCommentsBtn = document.getElementById('goto-comments');
    
    if (!gotoCommentsBtn) {
        return;
    }
    
    window.addEventListener('scroll', function() {
        // 当滚动超过 300px 且不在评论区时显示
        var commentsSection = document.getElementById('comments');
        
        if (!commentsSection) {
            return;
        }
        
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var commentsTop = commentsSection.offsetTop;
        var windowHeight = window.innerHeight;
        
        // 如果滚动超过 300px 且还没到评论区，显示按钮
        if (scrollTop > 300 && (scrollTop + windowHeight) < (commentsTop + 200)) {
            gotoCommentsBtn.classList.add('show');
        } else {
            gotoCommentsBtn.classList.remove('show');
        }
    });
    
    // 平滑滚动到评论区
    gotoCommentsBtn.addEventListener('click', function(e) {
        e.preventDefault();
        var commentsSection = document.getElementById('comments');
        
        if (commentsSection) {
            commentsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
})();
</script>
<?php endif; ?>

<footer id="footer" class="site-footer">
    <div class="container">
        <?php
        // 获取页脚设置
        $footer_copyright = get_theme_mod('moe_footer_copyright', '');
        $footer_icp = get_theme_mod('moe_footer_icp', '');
        $footer_gaba = get_theme_mod('moe_footer_gaba', '');
        $footer_gaba_url = get_theme_mod('moe_footer_gaba_url', '');
        $footer_links = get_theme_mod('moe_footer_links', false);
        $footer_theme_credit = get_theme_mod('moe_footer_theme_credit', true);
        $footer_load_time = get_theme_mod('moe_footer_load_time', false);
        ?>
        
        <?php if ($footer_links) : ?>
            <div class="footer-links-section">
                <?php if (has_nav_menu('footer')) : ?>
                    <p class="footer-links">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-link-list',
                            'items_wrap'     => '<span class="footer-link-list">%3$s</span>',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </p>
                <?php else : ?>
                    <?php
                    // 如果没有页脚菜单，尝试显示WordPress链接
                    $bookmarks = get_bookmarks(array('limit' => 10));
                    if ($bookmarks) : ?>
                        <p class="footer-links">
                            <span class="footer-link-list">
                                <?php foreach ($bookmarks as $bookmark) : ?>
                                    <a href="<?php echo esc_url($bookmark->link_url); ?>" target="_blank" rel="nofollow">
                                        <?php echo esc_html($bookmark->link_name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </span>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="site-info">
            <p class="copyright">
                <?php if ($footer_copyright) : ?>
                    <?php echo wp_kses_post($footer_copyright); ?>
                <?php else : ?>
                    &copy; <?php echo date('Y'); ?> 
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                    <?php _e('保留所有权利。', 'moe'); ?>
                <?php endif; ?>
                
                <?php if ($footer_icp) : ?>
                    &nbsp;<a rel="nofollow" target="_blank" href="http://www.beian.miit.gov.cn/"><?php echo esc_html($footer_icp); ?></a>
                <?php endif; ?>
                
                <?php if ($footer_gaba) : ?>
                    &nbsp;<a rel="nofollow" target="_blank" href="<?php echo esc_url($footer_gaba_url); ?>">
                        <?php echo esc_html($footer_gaba); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($footer_theme_credit) : ?>
                    &nbsp;&nbsp;<?php _e('基于', 'moe'); ?> 
                    <a href="<?php echo esc_url(__('https://wordpress.org/', 'moe')); ?>" target="_blank" rel="noopener noreferrer">
                        WordPress
                    </a>
                    <?php _e('构建，使用', 'moe'); ?> 
                    <a href="https://github.com/motao123/moe-wordpress-theme" target="_blank" rel="noopener noreferrer">
                        MOE Theme
                    </a>
                <?php endif; ?>
                
                <?php if ($footer_load_time) : ?>
                    &nbsp;&nbsp;<?php _e('页面生成时间：', 'moe'); ?>
                    <?php timer_stop(1); ?>
                    <?php _e('秒', 'moe'); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer><!-- /#footer -->

<?php wp_footer(); ?>

<script>
// 返回顶部
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// 控制返回顶部按钮显示/隐藏
window.addEventListener('scroll', function() {
    var topButton = document.getElementById('top');
    var topbar = document.getElementById('topbar');
    
    // 返回顶部按钮控制
    if (topButton) {
        if (window.pageYOffset > 300) {
            topButton.classList.add('show');
        } else {
            topButton.classList.remove('show');
        }
    }
    
    // 顶部导航固定
    if (topbar) {
        if (window.pageYOffset > 294) {
            topbar.classList.remove('affix-top');
            topbar.classList.add('affix');
        } else {
            topbar.classList.remove('affix');
            topbar.classList.add('affix-top');
        }
    }
});
</script>

</body>
</html>

