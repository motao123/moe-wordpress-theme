<?php
/**
 * 文章点赞功能
 * Post Likes Feature
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 获取文章点赞数
 */
function moe_get_post_likes( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $likes = get_post_meta( $post_id, 'post_likes_count', true );
    
    if ( empty( $likes ) ) {
        $likes = 0;
        add_post_meta( $post_id, 'post_likes_count', 0, true );
    }
    
    return intval( $likes );
}

/**
 * 检查用户是否已点赞
 */
function moe_user_has_liked( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    // 检查 Cookie
    $liked_posts = isset( $_COOKIE['moe_liked_posts'] ) ? json_decode( stripslashes( $_COOKIE['moe_liked_posts'] ), true ) : array();
    
    if ( ! is_array( $liked_posts ) ) {
        $liked_posts = array();
    }
    
    return in_array( $post_id, $liked_posts );
}

/**
 * Ajax 处理点赞
 */
function moe_ajax_like_post() {
    // 验证 nonce
    check_ajax_referer( 'moe_like_nonce', 'nonce' );
    
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    
    if ( ! $post_id ) {
        wp_send_json_error( array(
            'message' => __( '无效的文章ID', 'moe' )
        ) );
    }
    
    // 检查文章是否存在
    if ( ! get_post( $post_id ) ) {
        wp_send_json_error( array(
            'message' => __( '文章不存在', 'moe' )
        ) );
    }
    
    // 速率限制检查
    if ( ! moe_rate_limit_check( 'like_post', 10, 60 ) ) {
        wp_send_json_error( array(
            'message' => __( '操作太频繁，请稍后再试', 'moe' )
        ) );
    }
    
    // 检查是否已点赞
    $liked_posts = isset( $_COOKIE['moe_liked_posts'] ) ? json_decode( stripslashes( $_COOKIE['moe_liked_posts'] ), true ) : array();
    
    if ( ! is_array( $liked_posts ) ) {
        $liked_posts = array();
    }
    
    $action = 'like';
    $message = __( '点赞成功！', 'moe' );
    
    if ( in_array( $post_id, $liked_posts ) ) {
        // 取消点赞
        $liked_posts = array_diff( $liked_posts, array( $post_id ) );
        $action = 'unlike';
        $message = __( '已取消点赞', 'moe' );
        
        // 减少点赞数
        $current_likes = moe_get_post_likes( $post_id );
        $new_likes = max( 0, $current_likes - 1 );
        update_post_meta( $post_id, 'post_likes_count', $new_likes );
    } else {
        // 添加点赞
        $liked_posts[] = $post_id;
        $action = 'like';
        
        // 增加点赞数
        $current_likes = moe_get_post_likes( $post_id );
        $new_likes = $current_likes + 1;
        update_post_meta( $post_id, 'post_likes_count', $new_likes );
    }
    
    // 更新 Cookie（保存30天）
    setcookie( 'moe_liked_posts', json_encode( array_values( $liked_posts ) ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    $response = array(
        'action'  => $action,
        'message' => $message,
        'likes'   => moe_get_post_likes( $post_id ),
        'liked'   => in_array( $post_id, $liked_posts )
    );
    
    wp_send_json_success( $response );
}
add_action( 'wp_ajax_like_post', 'moe_ajax_like_post' );
add_action( 'wp_ajax_nopriv_like_post', 'moe_ajax_like_post' );

/**
 * 速率限制检查
 */
function moe_rate_limit_check( $action, $limit = 10, $period = 60 ) {
    $user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
    $transient_key = 'rate_limit_' . $action . '_' . md5( $user_ip );
    $count = get_transient( $transient_key );
    
    if ( false === $count ) {
        set_transient( $transient_key, 1, $period );
        return true;
    }
    
    if ( $count >= $limit ) {
        return false;
    }
    
    set_transient( $transient_key, $count + 1, $period );
    return true;
}

/**
 * 显示点赞按钮
 */
function moe_display_like_button( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $likes = moe_get_post_likes( $post_id );
    $user_liked = moe_user_has_liked( $post_id );
    $liked_class = $user_liked ? 'liked' : '';
    $nonce = wp_create_nonce( 'moe_like_nonce' );
    
    ?>
    <div class="post-like-wrapper">
        <button 
            class="post-like-btn <?php echo esc_attr( $liked_class ); ?>" 
            data-post-id="<?php echo esc_attr( $post_id ); ?>"
            data-nonce="<?php echo esc_attr( $nonce ); ?>"
            title="<?php echo $user_liked ? esc_attr__( '取消点赞', 'moe' ) : esc_attr__( '喜欢这篇文章', 'moe' ); ?>"
        >
            <i class="fa fa-heart<?php echo $user_liked ? '' : '-o'; ?>"></i>
            <span class="like-count"><?php echo esc_html( $likes ); ?></span>
            <span class="like-text"><?php echo $user_liked ? __( '已喜欢', 'moe' ) : __( '喜欢', 'moe' ); ?></span>
        </button>
    </div>
    <?php
}

/**
 * 在文章底部显示点赞按钮
 */
function moe_add_like_button_to_content( $content ) {
    if ( is_single() && is_main_query() ) {
        ob_start();
        ?>
        <div class="post-actions">
            <?php moe_display_like_button(); ?>
        </div>
        <?php
        $like_button = ob_get_clean();
        $content .= $like_button;
    }
    return $content;
}
// add_filter( 'the_content', 'moe_add_like_button_to_content' ); // 可选：自动添加到内容后

/**
 * 在 WordPress 仪表盘显示点赞数
 */
function moe_add_likes_column( $columns ) {
    $columns['post_likes'] = '<i class="fa fa-heart"></i> ' . __( '点赞数', 'moe' );
    return $columns;
}
add_filter( 'manage_posts_columns', 'moe_add_likes_column' );

/**
 * 显示点赞数列内容
 */
function moe_display_likes_column( $column, $post_id ) {
    if ( 'post_likes' === $column ) {
        $likes = moe_get_post_likes( $post_id );
        echo '<strong>' . esc_html( $likes ) . '</strong>';
    }
}
add_action( 'manage_posts_custom_column', 'moe_display_likes_column', 10, 2 );

/**
 * 使点赞数列可排序
 */
function moe_make_likes_column_sortable( $columns ) {
    $columns['post_likes'] = 'post_likes';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'moe_make_likes_column_sortable' );

/**
 * 处理点赞数排序
 */
function moe_likes_column_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    if ( 'post_likes' === $orderby ) {
        $query->set( 'meta_key', 'post_likes_count' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
add_action( 'pre_get_posts', 'moe_likes_column_orderby' );

/**
 * 添加点赞样式
 */
function moe_like_button_styles() {
    ?>
    <style>
    /* 点赞按钮样式 */
    .post-actions {
        margin: 30px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }
    
    .post-like-wrapper {
        display: inline-block;
    }
    
    .post-like-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .post-like-btn:hover {
        border-color: #FF6B9D;
        color: #FF6B9D;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.2);
    }
    
    .post-like-btn.liked {
        background: linear-gradient(135deg, #FF6B9D 0%, #FFA06B 100%);
        border-color: #FF6B9D;
        color: #fff;
    }
    
    .post-like-btn.liked:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
    }
    
    .post-like-btn i {
        font-size: 20px;
        transition: all 0.3s ease;
    }
    
    .post-like-btn.liked i {
        animation: heartBeat 0.5s ease;
    }
    
    .post-like-btn.liking i {
        animation: heartPulse 0.6s ease infinite;
    }
    
    .like-count {
        font-weight: 700;
        min-width: 20px;
    }
    
    .like-text {
        font-size: 14px;
    }
    
    /* 动画效果 */
    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.3); }
        50% { transform: scale(1.1); }
        75% { transform: scale(1.2); }
    }
    
    @keyframes heartPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .post-actions {
            margin: 20px 0;
            padding: 15px;
        }
        
        .post-like-btn {
            padding: 10px 20px;
            font-size: 14px;
        }
        
        .post-like-btn i {
            font-size: 18px;
        }
        
        .like-text {
            font-size: 13px;
        }
    }
    
    /* 加载动画 */
    .post-like-btn.loading {
        pointer-events: none;
        opacity: 0.6;
    }
    
    .post-like-btn.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_like_button_styles' );

/**
 * 添加点赞 JavaScript
 */
function moe_like_button_scripts() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.post-like-btn').on('click', function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var postId = $btn.data('post-id');
            var nonce = $btn.data('nonce');
            
            // 防止重复点击
            if ($btn.hasClass('loading')) {
                return;
            }
            
            $btn.addClass('loading liking');
            
            $.ajax({
                url: moeAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'like_post',
                    post_id: postId,
                    nonce: nonce
                },
                success: function(response) {
                    $btn.removeClass('loading liking');
                    
                    if (response.success) {
                        var data = response.data;
                        
                        // 更新点赞数
                        $btn.find('.like-count').text(data.likes);
                        
                        // 更新样式和图标
                        if (data.action === 'like') {
                            $btn.addClass('liked');
                            $btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                            $btn.find('.like-text').text('<?php echo esc_js( __( '已喜欢', 'moe' ) ); ?>');
                            $btn.attr('title', '<?php echo esc_js( __( '取消点赞', 'moe' ) ); ?>');
                        } else {
                            $btn.removeClass('liked');
                            $btn.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                            $btn.find('.like-text').text('<?php echo esc_js( __( '喜欢', 'moe' ) ); ?>');
                            $btn.attr('title', '<?php echo esc_js( __( '喜欢这篇文章', 'moe' ) ); ?>');
                        }
                        
                        // 显示提示（可选）
                        // alert(data.message);
                    } else {
                        alert(response.data.message || '<?php echo esc_js( __( '操作失败，请重试', 'moe' ) ); ?>');
                    }
                },
                error: function() {
                    $btn.removeClass('loading liking');
                    alert('<?php echo esc_js( __( '网络错误，请重试', 'moe' ) ); ?>');
                }
            });
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_like_button_scripts' );

