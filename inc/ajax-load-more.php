<?php
/**
 * Ajax 加载更多文章功能
 * Ajax Load More Posts
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Ajax 处理加载更多文章
 */
function moe_ajax_load_more_posts() {
    // 验证 nonce
    check_ajax_referer( 'moe_load_more_nonce', 'nonce' );
    
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : get_option( 'posts_per_page' );
    $category = isset( $_POST['category'] ) ? intval( $_POST['category'] ) : 0;
    
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'paged'          => $paged,
        'posts_per_page' => $posts_per_page,
    );
    
    // 如果指定了分类
    if ( $category ) {
        $args['cat'] = $category;
    }
    
    $query = new WP_Query( $args );
    
    if ( ! $query->have_posts() ) {
        wp_send_json_error( array(
            'message' => __( '没有更多文章了', 'moe' )
        ) );
    }
    
    ob_start();
    
    while ( $query->have_posts() ) {
        $query->the_post();
        
        // 使用现有的文章模板
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post scroll-reveal' ); ?>>
            <h2 class="post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="browser">
                    <div class="browser-view">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
                        </a>
                    </div>
                    <div class="b-overlay"></div>
                </div>
            <?php endif; ?>
            
            <p class="theme-excerpt">
                <?php echo wp_trim_words( get_the_excerpt(), 60 ); ?>
            </p>
            
            <ul class="post-meta bottom group">
                <li>
                    <i class="fa fa-folder-o"></i>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                    }
                    ?>
                </li>
                <li>
                    <i class="fa fa-clock-o"></i>
                    <?php echo get_the_date(); ?>
                </li>
                <li>
                    <a href="<?php comments_link(); ?>">
                        <i class="fa fa-comment"></i>
                        <?php
                        $comments_count = get_comments_number();
                        if ( $comments_count == 0 ) {
                            _e( '抢沙发', 'moe' );
                        } else {
                            printf( _n( '%s 条评论', '%s 条评论', $comments_count, 'moe' ), number_format_i18n( $comments_count ) );
                        }
                        ?>
                    </a>
                </li>
                <li>
                    <i class="fa fa-eye"></i>
                    <?php echo moe_get_post_views( get_the_ID() ); ?> <?php _e( '次', 'moe' ); ?>
                </li>
            </ul>
        </article>
        <?php
    }
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    $response = array(
        'html'     => $html,
        'page'     => $paged,
        'max_page' => $query->max_num_pages,
        'has_more' => $paged < $query->max_num_pages
    );
    
    wp_send_json_success( $response );
}
add_action( 'wp_ajax_load_more_posts', 'moe_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_load_more_posts', 'moe_ajax_load_more_posts' );

/**
 * 添加加载更多按钮到文章列表底部
 */
function moe_add_load_more_button() {
    if ( ! is_home() && ! is_archive() ) {
        return;
    }
    
    global $wp_query;
    
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }
    
    $current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $category = is_category() ? get_query_var( 'cat' ) : 0;
    $nonce = wp_create_nonce( 'moe_load_more_nonce' );
    ?>
    <div class="load-more-wrapper">
        <button 
            class="load-more-btn" 
            data-page="<?php echo esc_attr( $current_page ); ?>"
            data-max-page="<?php echo esc_attr( $wp_query->max_num_pages ); ?>"
            data-category="<?php echo esc_attr( $category ); ?>"
            data-nonce="<?php echo esc_attr( $nonce ); ?>"
        >
            <i class="fa fa-refresh"></i>
            <span class="btn-text"><?php _e( '加载更多', 'moe' ); ?></span>
        </button>
        <div class="load-more-status">
            <div class="load-more-loading">
                <i class="fa fa-spinner fa-spin"></i>
                <?php _e( '加载中...', 'moe' ); ?>
            </div>
            <div class="load-more-no-more">
                <i class="fa fa-check"></i>
                <?php _e( '没有更多文章了', 'moe' ); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * 添加样式
 */
function moe_load_more_styles() {
    if ( ! is_home() && ! is_archive() ) {
        return;
    }
    ?>
    <style>
    /* 加载更多按钮 */
    .load-more-wrapper {
        text-align: center;
        margin: 40px 0;
        padding: 20px;
    }
    
    .load-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 40px;
        background: linear-gradient(135deg, #24a5db 0%, #1a8fbd 100%);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(36, 165, 219, 0.3);
    }
    
    .load-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(36, 165, 219, 0.4);
    }
    
    .load-more-btn:active {
        transform: translateY(0);
    }
    
    .load-more-btn i {
        font-size: 18px;
        transition: transform 0.6s ease;
    }
    
    .load-more-btn.loading i {
        animation: rotate 1s linear infinite;
    }
    
    .load-more-btn.loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .load-more-status {
        margin-top: 20px;
    }
    
    .load-more-loading,
    .load-more-no-more {
        display: none;
        color: #666;
        font-size: 14px;
    }
    
    .load-more-loading i,
    .load-more-no-more i {
        margin-right: 8px;
    }
    
    .load-more-wrapper.loading .load-more-btn {
        display: none;
    }
    
    .load-more-wrapper.loading .load-more-loading {
        display: block;
    }
    
    .load-more-wrapper.no-more .load-more-btn {
        display: none;
    }
    
    .load-more-wrapper.no-more .load-more-no-more {
        display: block;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* 新加载文章的淡入动画 */
    .post.ajax-loaded {
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .load-more-wrapper {
            margin: 30px 0;
        }
        
        .load-more-btn {
            padding: 12px 30px;
            font-size: 14px;
        }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_load_more_styles' );

/**
 * 添加 JavaScript
 */
function moe_load_more_scripts() {
    if ( ! is_home() && ! is_archive() ) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        var loading = false;
        
        // 点击加载更多
        $('.load-more-btn').on('click', function(e) {
            e.preventDefault();
            
            if (loading) {
                return;
            }
            
            var $btn = $(this);
            var $wrapper = $btn.closest('.load-more-wrapper');
            var currentPage = parseInt($btn.data('page'));
            var maxPage = parseInt($btn.data('max-page'));
            var category = $btn.data('category');
            var nonce = $btn.data('nonce');
            var nextPage = currentPage + 1;
            
            if (nextPage > maxPage) {
                $wrapper.addClass('no-more');
                return;
            }
            
            loading = true;
            $wrapper.addClass('loading');
            $btn.addClass('loading');
            
            $.ajax({
                url: moeAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_more_posts',
                    page: nextPage,
                    category: category,
                    nonce: nonce
                },
                success: function(response) {
                    loading = false;
                    $wrapper.removeClass('loading');
                    $btn.removeClass('loading');
                    
                    if (response.success) {
                        var $html = $(response.data.html);
                        $html.addClass('ajax-loaded');
                        
                        // 插入新文章到列表末尾
                        $wrapper.before($html);
                        
                        // 更新页码
                        $btn.data('page', nextPage);
                        
                        // 如果没有更多了
                        if (!response.data.has_more) {
                            $wrapper.addClass('no-more');
                        }
                        
                        // 触发滚动显示动画
                        if (typeof initScrollReveal === 'function') {
                            setTimeout(function() {
                                initScrollReveal();
                            }, 100);
                        }
                    } else {
                        $wrapper.addClass('no-more');
                    }
                },
                error: function() {
                    loading = false;
                    $wrapper.removeClass('loading');
                    $btn.removeClass('loading');
                    alert('<?php echo esc_js( __( '加载失败，请重试', 'moe' ) ); ?>');
                }
            });
        });
        
        // 可选：无限滚动（取消注释以启用）
        /*
        var infiniteScroll = true;
        
        if (infiniteScroll) {
            $(window).on('scroll', function() {
                if (loading) {
                    return;
                }
                
                var $btn = $('.load-more-btn');
                if ($btn.length === 0) {
                    return;
                }
                
                var btnOffset = $btn.offset().top;
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();
                
                // 当按钮距离视窗底部还有 200px 时触发加载
                if (scrollTop + windowHeight > btnOffset - 200) {
                    $btn.trigger('click');
                }
            });
        }
        */
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_load_more_scripts' );

