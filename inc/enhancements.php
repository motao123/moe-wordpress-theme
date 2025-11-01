<?php
/**
 * MOE主题增强功能
 * Theme Enhancements
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 相关文章推荐
 */
if ( ! function_exists( 'moe_related_posts' ) ) {
    function moe_related_posts() {
        if ( ! is_single() ) {
            return;
        }
        
        global $post;
        
        // 获取当前文章的标签和分类
        $tags = wp_get_post_tags( $post->ID );
        $categories = get_the_category( $post->ID );
        
        // 构建查询参数
        if ( ! empty( $tags ) ) {
            // 优先使用标签匹配
            $tag_ids = array();
            foreach( $tags as $tag ) {
                $tag_ids[] = $tag->term_id;
            }
            
            $args = array(
                'tag__in'             => $tag_ids,
                'post__not_in'        => array( $post->ID ),
                'posts_per_page'      => 4,
                'ignore_sticky_posts' => 1,
                'orderby'             => 'rand',
            );
        } elseif ( ! empty( $categories ) ) {
            // 其次使用分类匹配
            $category_ids = array();
            foreach ( $categories as $category ) {
                $category_ids[] = $category->term_id;
            }
            
            $args = array(
                'category__in'        => $category_ids,
                'post__not_in'        => array( $post->ID ),
                'posts_per_page'      => 4,
                'ignore_sticky_posts' => 1,
                'orderby'             => 'rand',
            );
        } else {
            // 如果没有标签和分类，显示最新文章
            $args = array(
                'post__not_in'        => array( $post->ID ),
                'posts_per_page'      => 4,
                'ignore_sticky_posts' => 1,
                'orderby'             => 'date',
                'order'               => 'DESC',
            );
        }
        
        $related_query = new WP_Query( $args );
        
        if ( ! $related_query->have_posts() ) {
            return;
        }
        ?>
        
        <div class="related-posts-wrapper">
            <div class="related-posts">
                <h3 class="related-title">
                    <i class="fa fa-heart"></i>
                    <?php _e( '相关推荐', 'moe' ); ?>
                </h3>
                <div class="related-posts-grid">
                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                        <article class="related-post-item">
                            <a href="<?php the_permalink(); ?>" class="related-post-link">
                                <div class="related-post-thumb">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'moe-small', array( 'loading' => 'lazy' ) ); ?>
                                    <?php else : ?>
                                        <div class="related-post-no-thumb">
                                            <i class="fa fa-file-text-o"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="related-post-overlay">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                </div>
                                <div class="related-post-content">
                                    <h4 class="related-post-title"><?php the_title(); ?></h4>
                                    <div class="related-post-meta">
                                        <span class="related-post-date">
                                            <i class="fa fa-calendar-o"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <?php if ( function_exists( 'moe_get_post_views' ) ) : ?>
                                            <span class="related-post-views">
                                                <i class="fa fa-eye"></i>
                                                <?php echo moe_get_post_views( get_the_ID() ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        
        <!-- 相关推荐样式 -->
        <style>
        .related-posts-wrapper {
            margin: 40px 0;
            padding: 0;
        }
        
        .related-posts {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .related-posts .related-title {
            color: #333;
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 25px 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #FF6B9D;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .related-posts .related-title i {
            font-size: 24px;
            color: #FF6B9D;
        }
        
        .related-posts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        
        .related-post-item {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .related-post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: #FF6B9D;
        }
        
        .related-post-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        
        .related-post-thumb {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--moe-header-gradient-start) 0%, var(--moe-header-gradient-end) 100%);
        }
        
        .related-post-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .related-post-item:hover .related-post-thumb img {
            transform: scale(1.1);
        }
        
        .related-post-no-thumb {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 48px;
        }
        
        .related-post-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 107, 157, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .related-post-overlay i {
            color: #fff;
            font-size: 32px;
            transform: translateX(-10px);
            transition: transform 0.3s ease;
        }
        
        .related-post-item:hover .related-post-overlay {
            opacity: 1;
        }
        
        .related-post-item:hover .related-post-overlay i {
            transform: translateX(0);
        }
        
        .related-post-content {
            padding: 15px;
        }
        
        .related-post-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 10px 0;
            line-height: 1.5;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 45px;
        }
        
        .related-post-item:hover .related-post-title {
            color: #FF6B9D;
        }
        
        .related-post-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 13px;
            color: #999;
        }
        
        .related-post-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .related-post-meta i {
            font-size: 12px;
        }
        
        /* 响应式设计 */
        @media screen and (max-width: 1024px) {
            .related-posts-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
        }
        
        @media screen and (max-width: 768px) {
            .related-posts {
                padding: 20px;
            }
            
            .related-posts .related-title {
                font-size: 20px;
                margin-bottom: 20px;
            }
            
            .related-posts-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .related-post-thumb {
                height: 140px;
            }
            
            .related-post-title {
                font-size: 14px;
                min-height: auto;
            }
        }
        
        @media screen and (max-width: 480px) {
            .related-posts-wrapper {
                margin: 30px 0;
            }
            
            .related-posts {
                padding: 15px;
                border-radius: 8px;
            }
            
            .related-posts .related-title {
                font-size: 18px;
                margin-bottom: 15px;
                padding-bottom: 10px;
            }
            
            .related-posts-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .related-post-item {
                display: flex;
            }
            
            .related-post-thumb {
                width: 120px;
                height: 120px;
                flex-shrink: 0;
            }
            
            .related-post-content {
                flex: 1;
                padding: 10px;
            }
            
            .related-post-title {
                font-size: 14px;
                -webkit-line-clamp: 3;
            }
            
            .related-post-meta {
                font-size: 12px;
                gap: 10px;
            }
        }
        </style>
        
        <?php
        wp_reset_postdata();
    }
}

/**
 * 社交分享按钮
 */
if ( ! function_exists( 'moe_social_share' ) ) {
    function moe_social_share() {
        if ( ! is_single() ) {
            return;
        }
        
        $url   = urlencode( get_permalink() );
        $title = urlencode( get_the_title() );
        $desc  = urlencode( wp_trim_words( get_the_excerpt(), 50 ) );
        $image = has_post_thumbnail() ? urlencode( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) : '';
        ?>
        <div class="social-share-wrapper">
            <div class="social-share">
                <h3 class="share-title">
                    <i class="fa fa-share-alt"></i>
                    <?php _e( '分享到', 'moe' ); ?>
                </h3>
                <div class="share-buttons">
                    <!-- 微博 -->
                    <a href="https://service.weibo.com/share/share.php?url=<?php echo $url; ?>&title=<?php echo $title; ?>&pic=<?php echo $image; ?>" 
                       target="_blank" 
                       rel="nofollow noopener" 
                       class="share-btn share-weibo" 
                       title="<?php esc_attr_e( '分享到微博', 'moe' ); ?>">
                        <i class="fa fa-weibo"></i>
                        <span class="share-text"><?php _e( '微博', 'moe' ); ?></span>
                    </a>
                    
                    <!-- QQ空间 -->
                    <a href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo $url; ?>&title=<?php echo $title; ?>&pics=<?php echo $image; ?>" 
                       target="_blank" 
                       rel="nofollow noopener" 
                       class="share-btn share-qzone" 
                       title="<?php esc_attr_e( '分享到QQ空间', 'moe' ); ?>">
                        <i class="fa fa-qq"></i>
                        <span class="share-text"><?php _e( 'QQ空间', 'moe' ); ?></span>
                    </a>
                    
                    <!-- 豆瓣 -->
                    <a href="https://www.douban.com/share/service?url=<?php echo $url; ?>&title=<?php echo $title; ?>&image=<?php echo $image; ?>" 
                       target="_blank" 
                       rel="nofollow noopener" 
                       class="share-btn share-douban" 
                       title="<?php esc_attr_e( '分享到豆瓣', 'moe' ); ?>">
                        <i class="fa fa-bookmark"></i>
                        <span class="share-text"><?php _e( '豆瓣', 'moe' ); ?></span>
                    </a>
                    
                    <!-- 复制链接 -->
                    <button type="button" 
                            class="share-btn share-copy" 
                            data-url="<?php echo esc_attr( get_permalink() ); ?>" 
                            title="<?php esc_attr_e( '复制链接', 'moe' ); ?>">
                        <i class="fa fa-link"></i>
                        <span class="share-text"><?php _e( '复制链接', 'moe' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- 社交分享样式 -->
        <style>
        .social-share-wrapper {
            margin: 40px 0;
            padding: 30px;
            background: linear-gradient(135deg, var(--moe-header-gradient-start) 0%, var(--moe-header-gradient-end) 100%);
            border: 2px solid var(--moe-header-border-color);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(255, 182, 193, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .social-share-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: share-glow 8s linear infinite;
        }
        
        @keyframes share-glow {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-10%, -10%); }
        }
        
        .social-share {
            position: relative;
            z-index: 1;
        }
        
        .social-share .share-title {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .social-share .share-title i {
            font-size: 24px;
        }
        
        .share-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .share-btn {
            flex: 1;
            min-width: 140px;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 8px;
            color: #333;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .share-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .share-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .share-btn i {
            font-size: 20px;
            position: relative;
            z-index: 1;
        }
        
        .share-btn .share-text {
            position: relative;
            z-index: 1;
        }
        
        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .share-btn:active {
            transform: translateY(-1px);
        }
        
        /* 平台特定颜色 */
        .share-weibo:hover {
            background: #e6162d;
            color: #fff;
        }
        
        .share-qzone:hover {
            background: #12b7f5;
            color: #fff;
        }
        
        .share-douban:hover {
            background: #007722;
            color: #fff;
        }
        
        .share-copy:hover {
            background: #FF6B9D;
            color: #fff;
        }
        
        .share-copy.copied {
            background: #4CAF50;
            color: #fff;
        }
        
        .share-copy.copied i::before {
            content: "\f00c";
        }
        
        /* 响应式设计 */
        @media screen and (max-width: 768px) {
            .social-share-wrapper {
                padding: 20px;
                margin: 30px 0;
            }
            
            .social-share .share-title {
                font-size: 18px;
            }
            
            .share-buttons {
                gap: 10px;
            }
            
            .share-btn {
                min-width: calc(50% - 5px);
                padding: 12px 15px;
                font-size: 14px;
            }
        }
        
        @media screen and (max-width: 480px) {
            .social-share-wrapper {
                padding: 15px;
                margin: 20px 0;
            }
            
            .share-buttons {
                gap: 8px;
            }
            
            .share-btn {
                min-width: 100%;
                font-size: 13px;
            }
        }
        </style>
        
        <!-- 复制链接JavaScript -->
        <script>
        (function() {
            var copyButtons = document.querySelectorAll('.share-copy');
            
            copyButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var url = this.getAttribute('data-url');
                    
                    // 尝试使用现代 Clipboard API
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).then(function() {
                            showCopySuccess(button);
                        }).catch(function(err) {
                            fallbackCopyToClipboard(url, button);
                        });
                    } else {
                        fallbackCopyToClipboard(url, button);
                    }
                });
            });
            
            function fallbackCopyToClipboard(text, button) {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.top = "0";
                textArea.style.left = "0";
                textArea.style.width = "2em";
                textArea.style.height = "2em";
                textArea.style.padding = "0";
                textArea.style.border = "none";
                textArea.style.outline = "none";
                textArea.style.boxShadow = "none";
                textArea.style.background = "transparent";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    var successful = document.execCommand('copy');
                    if (successful) {
                        showCopySuccess(button);
                    } else {
                        alert('<?php _e( '复制失败，请手动复制链接', 'moe' ); ?>');
                    }
                } catch (err) {
                    alert('<?php _e( '复制失败，请手动复制链接', 'moe' ); ?>');
                }
                
                document.body.removeChild(textArea);
            }
            
            function showCopySuccess(button) {
                var originalText = button.querySelector('.share-text').textContent;
                button.classList.add('copied');
                button.querySelector('.share-text').textContent = '<?php _e( '已复制', 'moe' ); ?>';
                
                setTimeout(function() {
                    button.classList.remove('copied');
                    button.querySelector('.share-text').textContent = originalText;
                }, 2000);
            }
        })();
        </script>
        <?php
    }
}

/**
 * Ajax搜索
 */
function moe_ajax_search() {
    check_ajax_referer( 'moe-search-nonce', 'nonce' );
    
    $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
    
    if ( empty( $keyword ) ) {
        wp_send_json_error( array( 'message' => __( '请输入搜索关键词', 'moe' ) ) );
    }
    
    $args = array(
        's'              => $keyword,
        'posts_per_page' => 5,
    );
    
    $search_query = new WP_Query( $args );
    
    $results = array();
    
    if ( $search_query->have_posts() ) {
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
            $results[] = array(
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => wp_trim_words( get_the_excerpt(), 15 ),
                'date'      => get_the_date(),
                'thumbnail' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
            );
        }
        wp_send_json_success( $results );
    } else {
        wp_send_json_error( array( 'message' => __( '未找到相关结果', 'moe' ) ) );
    }
    
    wp_reset_postdata();
}
add_action( 'wp_ajax_moe_search', 'moe_ajax_search' );
add_action( 'wp_ajax_nopriv_moe_search', 'moe_ajax_search' );
