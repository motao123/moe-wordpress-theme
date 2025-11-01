<?php
/**
 * MOE WordPress Edition Functions
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Theme version
define( 'MOE_VERSION', '1.0.0' );

// 开发模式 (生产环境请设置为 false)
define( 'MOE_DEV_MODE', false );

/**
 * Theme Setup
 */
function moe_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'moe', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 800, 450, true );
    add_image_size( 'moe-large', 1200, 600, true );
    add_image_size( 'moe-medium', 600, 400, true );
    add_image_size( 'moe-small', 300, 200, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( '主导航菜单', 'moe' ),
        'footer'  => __( '底部菜单', 'moe' ),
    ) );
    
    // 启用链接管理器（用于友情链接）
    add_filter( 'pre_option_link_manager_enabled', '__return_true' );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ) );

    // Add theme support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 120,
        'width'       => 120,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );

    // Add support for wide and full alignment
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'moe_setup' );

/**
 * Set content width
 */
function moe_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'moe_content_width', 800 );
}
add_action( 'after_setup_theme', 'moe_content_width', 0 );

/**
 * Register Widget Areas
 */
function moe_widgets_init() {
    register_sidebar( array(
        'name'          => __( '主侧边栏', 'moe' ),
        'id'            => 'sidebar-1',
        'description'   => __( '显示在主内容区域旁边的小工具。', 'moe' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h3><span>',
        'after_title'   => '</span></h3>',
    ) );
}
add_action( 'widgets_init', 'moe_widgets_init' );

/**
 * 性能优化：资源预加载
 */
function moe_resource_hints( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        // DNS预解析外部资源
        $urls[] = '//ww1.sinaimg.cn'; // 头部背景图
        $urls[] = '//fonts.googleapis.com';
    }
    
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => '//ww1.sinaimg.cn',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'moe_resource_hints', 10, 2 );

/**
 * 性能优化：预加载关键资源
 */
function moe_preload_assets() {
    // 预加载 Font Awesome
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/css/font-awesome.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    echo '<noscript><link rel="stylesheet" href="' . get_template_directory_uri() . '/css/font-awesome.min.css"></noscript>';
    
    // 预加载字体文件
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/fonts/fontawesome-webfont.woff?v=4.1.0" as="font" type="font/woff" crossorigin>';
}
add_action( 'wp_head', 'moe_preload_assets', 1 );

/**
 * 性能优化：添加WebP图片支持
 */
function moe_webp_upload_mimes( $existing_mimes ) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter( 'mime_types', 'moe_webp_upload_mimes' );

/**
 * 性能优化：启用原生图片懒加载
 */
function moe_add_lazy_loading( $attr, $attachment, $size ) {
    if ( ! is_admin() ) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'moe_add_lazy_loading', 10, 3 );

/**
 * 性能优化：移除不必要的WordPress默认功能
 */
function moe_remove_unnecessary_features() {
    // 移除emoji脚本
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    
    // 移除WordPress版本号
    remove_action( 'wp_head', 'wp_generator' );
    
    // 移除WLW manifest链接
    remove_action( 'wp_head', 'wlwmanifest_link' );
    
    // 移除RSD链接
    remove_action( 'wp_head', 'rsd_link' );
    
    // 移除短链接
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    
    // 移除REST API链接（如果不需要）
    // remove_action( 'wp_head', 'rest_output_link_wp_head' );
    
    // 移除oEmbed发现链接
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'moe_remove_unnecessary_features' );

/**
 * 性能优化：禁用Gutenberg编辑器的前端样式（如果不需要）
 */
function moe_dequeue_block_styles() {
    if ( ! is_admin() ) {
        wp_dequeue_style( 'wp-block-library' ); // WordPress核心块样式
        wp_dequeue_style( 'wp-block-library-theme' ); // WordPress核心块主题样式
        wp_dequeue_style( 'wc-block-style' ); // WooCommerce块样式（如果安装）
    }
}
add_action( 'wp_enqueue_scripts', 'moe_dequeue_block_styles', 100 );

/**
 * ===========================================================================
 * 安全性增强
 * ===========================================================================
 */

/**
 * 安全性：添加HTTP安全头部
 */
function moe_security_headers() {
    // 防止点击劫持
    header( 'X-Frame-Options: SAMEORIGIN' );
    
    // 防止MIME类型嗅探
    header( 'X-Content-Type-Options: nosniff' );
    
    // XSS保护
    header( 'X-XSS-Protection: 1; mode=block' );
    
    // 引用来源策略
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    
    // 内容安全策略 (CSP) - 根据需要调整
    if ( ! is_admin() ) {
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ww1.sinaimg.cn; ";
        $csp .= "style-src 'self' 'unsafe-inline'; ";
        $csp .= "img-src 'self' data: https: http:; ";
        $csp .= "font-src 'self' data:; ";
        $csp .= "connect-src 'self'; ";
        $csp .= "media-src 'self'; ";
        $csp .= "object-src 'none'; ";
        $csp .= "frame-ancestors 'self';";
        
        header( "Content-Security-Policy: " . $csp );
    }
    
    // 功能策略
    header( "Permissions-Policy: geolocation=(), microphone=(), camera=()" );
}
add_action( 'send_headers', 'moe_security_headers' );

/**
 * 安全性：限制文件上传类型
 */
function moe_restrict_upload_mimes( $mimes ) {
    // 移除危险文件类型
    unset( $mimes['exe'] );
    unset( $mimes['com'] );
    unset( $mimes['bat'] );
    unset( $mimes['cmd'] );
    unset( $mimes['pif'] );
    unset( $mimes['scr'] );
    unset( $mimes['vbs'] );
    unset( $mimes['php'] );
    unset( $mimes['phtml'] );
    unset( $mimes['php3'] );
    unset( $mimes['php4'] );
    unset( $mimes['php5'] );
    unset( $mimes['php7'] );
    unset( $mimes['phps'] );
    
    return $mimes;
}
add_filter( 'upload_mimes', 'moe_restrict_upload_mimes' );

/**
 * 安全性：禁用文件编辑
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * 安全性：隐藏登录错误信息
 */
function moe_login_errors() {
    return '登录信息有误，请重试。';
}
add_filter( 'login_errors', 'moe_login_errors' );

/**
 * 安全性：限制登录尝试（基础版）
 * 注意：生产环境建议使用专业插件如 Limit Login Attempts
 */
function moe_check_attempted_login( $user, $username, $password ) {
    if ( get_transient( 'attempted_login_' . sanitize_user( $username ) ) ) {
        $datas = get_transient( 'attempted_login_' . sanitize_user( $username ) );
        
        if ( $datas['tried'] >= 3 ) {
            $until = get_option( '_transient_timeout_' . 'attempted_login_' . sanitize_user( $username ) );
            $time = time_to_go( $until );
            
            return new WP_Error( 'too_many_tried', sprintf( 
                __( '错误：登录尝试次数过多。请在 %s 后重试。', 'moe' ), 
                $time 
            ) );
        }
    }
    
    return $user;
}
add_filter( 'authenticate', 'moe_check_attempted_login', 30, 3 );

function moe_login_failed( $username ) {
    if ( get_transient( 'attempted_login_' . sanitize_user( $username ) ) ) {
        $datas = get_transient( 'attempted_login_' . sanitize_user( $username ) );
        $datas['tried']++;
        
        if ( $datas['tried'] <= 3 ) {
            set_transient( 'attempted_login_' . sanitize_user( $username ), $datas, 300 );
        }
    } else {
        $datas = array(
            'tried' => 1
        );
        set_transient( 'attempted_login_' . sanitize_user( $username ), $datas, 300 );
    }
}
add_action( 'wp_login_failed', 'moe_login_failed', 10, 1 );

function time_to_go( $timestamp ) {
    $periods = array( 
        '秒', 
        '分钟', 
        '小时', 
        '天' 
    );
    $lengths = array( 
        60, 
        60, 
        24 
    );
    
    $difference = $timestamp - time();
    
    for ( $i = 0; $difference >= $lengths[$i] && $i < count( $lengths ) - 1; $i++ ) {
        $difference /= $lengths[$i];
    }
    
    $difference = round( $difference );
    
    if ( $difference != 1 ) {
        $periods[$i] = $periods[$i];
    }
    
    return $difference . ' ' . $periods[$i];
}

/**
 * 安全性：移除WordPress版本信息
 */
function moe_remove_version_info() {
    return '';
}
add_filter( 'the_generator', 'moe_remove_version_info' );

/**
 * 安全性：禁用XML-RPC
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * 安全性：禁用REST API（非必需，根据需求开启）
 * 如果需要使用REST API，请注释掉以下代码
 */
// function moe_disable_rest_api( $access ) {
//     if ( ! is_user_logged_in() ) {
//         return new WP_Error( 
//             'rest_disabled', 
//             __( 'REST API已被禁用。', 'moe' ), 
//             array( 'status' => 403 ) 
//         );
//     }
//     return $access;
// }
// add_filter( 'rest_authentication_errors', 'moe_disable_rest_api' );

/**
 * 安全性：清理用户名中的特殊字符
 */
function moe_sanitize_username( $username ) {
    $username = strip_tags( $username );
    $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
    $username = preg_replace( '/&.+?;/', '', $username );
    
    return $username;
}
add_filter( 'sanitize_user', 'moe_sanitize_username', 10, 1 );

/**
 * 安全性：防止用户枚举
 */
function moe_prevent_user_enumeration() {
    if ( ! is_admin() && isset( $_REQUEST['author'] ) && is_numeric( $_REQUEST['author'] ) ) {
        wp_die( '禁止访问', '403 Forbidden', array( 'response' => 403 ) );
    }
}
add_action( 'init', 'moe_prevent_user_enumeration' );

/**
 * 安全性：为评论表单添加nonce验证
 */
function moe_add_comment_nonce( $defaults ) {
    $defaults['comment_field'] = wp_nonce_field( 'moe_comment_nonce', 'moe_comment_nonce_field', true, false ) . $defaults['comment_field'];
    return $defaults;
}
add_filter( 'comment_form_defaults', 'moe_add_comment_nonce' );

/**
 * 安全性：验证评论nonce
 */
function moe_verify_comment_nonce( $commentdata ) {
    if ( ! isset( $_POST['moe_comment_nonce_field'] ) || 
         ! wp_verify_nonce( $_POST['moe_comment_nonce_field'], 'moe_comment_nonce' ) ) {
        wp_die( 
            __( '安全验证失败，请刷新页面后重试。', 'moe' ), 
            __( '安全验证失败', 'moe' ), 
            array( 'response' => 403 ) 
        );
    }
    return $commentdata;
}
add_filter( 'preprocess_comment', 'moe_verify_comment_nonce' );

/**
 * 引入主题核心文件
 */
$core_files = array(
	'widgets.php',
	'customizer.php',
	'enhancements.php',
);

foreach ( $core_files as $file ) {
	$path = get_template_directory() . '/inc/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	} else {
		// 记录错误日志
		error_log( 'MOE Theme: Missing core file - ' . $file );
	}
}

/**
 * 引入 SEO 增强功能（v2.2.5+）
 * 包括：Open Graph、Schema.org 结构化数据、元描述等
 */
if ( file_exists( get_template_directory() . '/inc/seo-enhancements.php' ) ) {
    require_once get_template_directory() . '/inc/seo-enhancements.php';
}

/**
 * 引入文章点赞功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/post-likes.php' ) ) {
    require_once get_template_directory() . '/inc/post-likes.php';
}

/**
 * 引入 Ajax 加载更多功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/ajax-load-more.php' ) ) {
    require_once get_template_directory() . '/inc/ajax-load-more.php';
}

/**
 * 引入阅读进度条功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/reading-progress.php' ) ) {
    require_once get_template_directory() . '/inc/reading-progress.php';
}

/**
 * 引入代码复制按钮功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/code-copy-button.php' ) ) {
    require_once get_template_directory() . '/inc/code-copy-button.php';
}

/**
 * 引入字数统计与阅读时间功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/reading-time.php' ) ) {
    require_once get_template_directory() . '/inc/reading-time.php';
}

/**
 * 引入字体大小调节功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/font-size-control.php' ) ) {
    require_once get_template_directory() . '/inc/font-size-control.php';
}

/**
 * 引入文章目录（TOC）功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/table-of-contents.php' ) ) {
    require_once get_template_directory() . '/inc/table-of-contents.php';
}

/**
 * 引入图片灯箱功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/image-lightbox.php' ) ) {
    require_once get_template_directory() . '/inc/image-lightbox.php';
}

/**
 * 引入暗色模式功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/dark-mode.php' ) ) {
    require_once get_template_directory() . '/inc/dark-mode.php';
}

/**
 * 引入归档时间轴功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/archive-timeline.php' ) ) {
    require_once get_template_directory() . '/inc/archive-timeline.php';
}

/**
 * 引入友情链接检测功能（v2.3.0+）
 */
if ( file_exists( get_template_directory() . '/inc/link-checker.php' ) ) {
    require_once get_template_directory() . '/inc/link-checker.php';
}

/**
 * Enqueue Scripts and Styles
 */
function moe_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'moe-style', get_stylesheet_uri(), array(), MOE_VERSION );

    // Enqueue Font Awesome
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0' );

    // 开发模式：分别加载CSS文件
    // 生产模式：合并内联关键CSS
    if ( MOE_DEV_MODE || WP_DEBUG ) {
        // Enqueue custom styles if exists
        if ( file_exists( get_template_directory() . '/css/styles.css' ) ) {
            wp_enqueue_style( 'moe-custom-styles', get_template_directory_uri() . '/css/styles.css', array(), MOE_VERSION );
        }
        
        // Enqueue fix styles
        if ( file_exists( get_template_directory() . '/css/fix.css' ) ) {
            wp_enqueue_style( 'moe-fix-styles', get_template_directory_uri() . '/css/fix.css', array( 'moe-style' ), MOE_VERSION );
        }
        
        // Enqueue code fix styles (高优先级)
        if ( file_exists( get_template_directory() . '/css/code-fix.css' ) ) {
            wp_enqueue_style( 'moe-code-fix', get_template_directory_uri() . '/css/code-fix.css', array( 'moe-style', 'moe-fix-styles' ), MOE_VERSION );
        }
        
        // Enqueue index layout styles
        if ( is_home() || is_archive() || is_search() ) {
            wp_enqueue_style( 'moe-index-layout', get_template_directory_uri() . '/css/index-layout.css', array( 'moe-style' ), MOE_VERSION );
        }
    } else {
        // 生产模式：合并CSS为内联样式以减少HTTP请求
        $inline_css = '';
        
        // 合并自定义CSS
        $css_files = array(
            '/css/styles.css',
            '/css/fix.css',
            '/css/code-fix.css'
        );
        
        if ( is_home() || is_archive() || is_search() ) {
            $css_files[] = '/css/index-layout.css';
        }
        
        foreach ( $css_files as $css_file ) {
            $file_path = get_template_directory() . $css_file;
            if ( file_exists( $file_path ) ) {
                $inline_css .= file_get_contents( $file_path );
            }
        }
        
        if ( ! empty( $inline_css ) ) {
            // 移除CSS注释以减小体积
            $inline_css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $inline_css );
            // 移除多余空格
            $inline_css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $inline_css );
            
            wp_add_inline_style( 'moe-style', $inline_css );
        }
    }

    // Enqueue jQuery (already included in WordPress)
    wp_enqueue_script( 'jquery' );

    // Enqueue custom scripts
    if ( file_exists( get_template_directory() . '/js/moe-script.js' ) ) {
        wp_enqueue_script( 'moe-script', get_template_directory_uri() . '/js/moe-script.js', array( 'jquery' ), MOE_VERSION, true );
    }

    // Enqueue comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Localize script for AJAX
    wp_localize_script( 'moe-script', 'moeAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'moe-nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'moe_scripts' );

/**
 * Custom Walker for Navigation Menu (Multi-level support)
 */
class MOE_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu level-$depth\">\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-parent', $classes ) ) {
            $classes[] = 'current_page_item';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Get Avatar URL
 */
function moe_get_avatar_url( $email, $size = 80 ) {
    $hash = md5( strtolower( trim( $email ) ) );
    return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mm&r=g";
}

/**
 * Custom Excerpt Length
 */
function moe_excerpt_length( $length ) {
    return 120;
}
add_filter( 'excerpt_length', 'moe_excerpt_length', 999 );

/**
 * Custom Excerpt More
 */
function moe_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'moe_excerpt_more' );

/**
 * Custom Comment List
 */
function moe_comment_list( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract( $args, EXTR_SKIP );

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
    
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID(); ?>" class="comment">
    <?php endif; ?>
    
        <div class="avatar">
            <?php 
            if ( $args['avatar_size'] != 0 ) {
                echo get_avatar( $comment, $args['avatar_size'] ); 
            }
            ?>
        </div>
        
        <div class="comment-info">
            <span class="poster">
                <i class="fa fa-user mar-r-4 green"></i>
                <?php printf( '%s', get_comment_author_link() ); ?>
            </span>
            
            <span class="comment-time">
                <i class="fa fa-clock-o mar-r-4"></i>
                <?php echo get_comment_date() . ' ' . get_comment_time(); ?>
            </span>
            
            <span class="comment-reply">
                <?php 
                comment_reply_link( array_merge( $args, array( 
                    'add_below' => $add_below, 
                    'depth'     => $depth, 
                    'max_depth' => $args['max_depth'],
                    'before'    => '<i class="fa fa-share mar-r-4"></i>',
                ) ) ); 
                ?>
            </span>
            
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <em class="comment-awaiting-moderation"><?php _e( '您的评论正在等待审核。', 'moe' ); ?></em>
                <br />
            <?php endif; ?>
            
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
        </div>
        
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
    <?php
}

/**
 * Pagination
 */
if ( ! function_exists( 'moe_pagination' ) ) {
    function moe_pagination() {
        global $wp_query;

        $big = 999999999;

        $pages = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
            'prev_text' => '<i class="fa fa-arrow-circle-o-left"></i> ' . __( '上一页', 'moe' ),
            'next_text' => __( '下一页', 'moe' ) . ' <i class="fa fa-arrow-circle-o-right"></i>',
        ) );

        if ( is_array( $pages ) ) {
            echo '<div id="pagenavi"><ul class="pagination">';
            foreach ( $pages as $page ) {
                echo "<li>$page</li>";
            }
            echo '</ul></div>';
        }
    }
}

/**
 * Post Views Counter
 */
if ( ! function_exists( 'moe_set_post_views' ) ) {
    function moe_set_post_views( $post_id ) {
        $count_key = 'post_views_count';
        $count = get_post_meta( $post_id, $count_key, true );
        
        if ( $count == '' ) {
            $count = 0;
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '0' );
        } else {
            $count++;
            update_post_meta( $post_id, $count_key, $count );
        }
    }
}

if ( ! function_exists( 'moe_get_post_views' ) ) {
    function moe_get_post_views( $post_id ) {
        $count_key = 'post_views_count';
        $count = get_post_meta( $post_id, $count_key, true );
        
        if ( $count == '' ) {
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '0' );
            return '0';
        }
        
        return $count;
    }
}

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/**
 * Breadcrumbs
 */
if ( ! function_exists( 'moe_breadcrumbs' ) ) {
    function moe_breadcrumbs() {
        $delimiter = ' <i class="fa fa-angle-right"></i> ';
        $home = __( '首页', 'moe' );
        $before = '<span class="current">';
        $after = '</span>';

        if ( ! is_home() && ! is_front_page() || is_paged() ) {
            echo '<div id="breadcrumbs">';
            echo '<a href="' . esc_url( home_url() ) . '">' . $home . '</a>' . $delimiter;

        if ( is_category() ) {
            $cat = get_category( get_query_var( 'cat' ), false );
            if ( $cat->parent != 0 ) {
                echo get_category_parents( $cat->parent, true, $delimiter );
            }
            echo $before . single_cat_title( '', false ) . $after;
        } elseif ( is_day() ) {
            echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $delimiter;
            echo '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a>' . $delimiter;
            echo $before . get_the_time( 'd' ) . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $delimiter;
            echo $before . get_the_time( 'F' ) . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time( 'Y' ) . $after;
        } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object( get_post_type() );
                $slug = $post_type->rewrite;
                echo '<a href="' . home_url( '/' . $slug['slug'] . '/' ) . '">' . $post_type->labels->singular_name . '</a>' . $delimiter;
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category();
                if ( isset( $cat[0] ) ) {
                    echo get_category_parents( $cat[0], true, $delimiter );
                }
                echo $before . get_the_title() . $after;
            }
        } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' ) {
            $post_type = get_post_type_object( get_post_type() );
            echo $before . $post_type->labels->singular_name . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post( $post->post_parent );
            echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>' . $delimiter;
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && ! $post->post_parent ) {
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ( $parent_id ) {
                $page = get_post( $parent_id );
                $breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse( $breadcrumbs );
            foreach ( $breadcrumbs as $crumb ) {
                echo $crumb . $delimiter;
            }
            echo $before . get_the_title() . $after;
        } elseif ( is_search() ) {
            echo $before . __( '搜索结果：', 'moe' ) . get_search_query() . $after;
        } elseif ( is_tag() ) {
            echo $before . __( '标签：', 'moe' ) . single_tag_title( '', false ) . $after;
        } elseif ( is_author() ) {
            $userdata = get_userdata( get_query_var( 'author' ) );
            echo $before . __( '作者：', 'moe' ) . $userdata->display_name . $after;
        } elseif ( is_404() ) {
            echo $before . __( '404 错误', 'moe' ) . $after;
        }

        if ( get_query_var( 'paged' ) ) {
            echo ' (' . __( '第', 'moe' ) . ' ' . get_query_var( 'paged' ) . ' ' . __( '页', 'moe' ) . ')';
        }

        echo '</div>';
        }
    }
}

/**
 * Time ago function
 */
function moe_time_ago( $time ) {
    $time_difference = current_time( 'timestamp' ) - $time;

    if ( $time_difference < 1 ) {
        return __( '刚刚', 'moe' );
    }

    $condition = array(
        12 * 30 * 24 * 60 * 60 => __( '年', 'moe' ),
        30 * 24 * 60 * 60      => __( '月', 'moe' ),
        24 * 60 * 60           => __( '天', 'moe' ),
        60 * 60                => __( '小时', 'moe' ),
        60                     => __( '分钟', 'moe' ),
        1                      => __( '秒', 'moe' )
    );

    foreach ( $condition as $secs => $str ) {
        $d = $time_difference / $secs;

        if ( $d >= 1 ) {
            $t = round( $d );
            return $t . ' ' . $str . __( '前', 'moe' );
        }
    }
}

/**
 * Add custom body classes
 */
function moe_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'single-post';
    }
    
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'has-sidebar';
    }
    
    return $classes;
}
add_filter( 'body_class', 'moe_body_classes' );

/**
 * Custom Logo
 */
function moe_custom_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        echo '<img src="' . get_template_directory_uri() . '/images/avatar.jpg" alt="' . get_bloginfo( 'name' ) . '">';
    }
}

/**
 * Social Links (可在主题设置中配置)
 */
function moe_get_social_links() {
    $social_links = array(
        'weibo'   => get_theme_mod( 'moe_weibo_url', '#' ),
        'qq'      => get_theme_mod( 'moe_qq_url', '#' ),
        'github'  => get_theme_mod( 'moe_github_url', '#' ),
    );
    
    return $social_links;
}

/**
 * Customizer Settings
 */
function moe_customize_register( $wp_customize ) {
    // Social Links Section
    $wp_customize->add_section( 'moe_social_links', array(
        'title'    => __( '社交链接', 'moe' ),
        'priority' => 30,
    ) );

    // Weibo URL
    $wp_customize->add_setting( 'moe_weibo_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'moe_weibo_url', array(
        'label'    => __( '微博链接', 'moe' ),
        'section'  => 'moe_social_links',
        'type'     => 'url',
    ) );

    // QQ URL
    $wp_customize->add_setting( 'moe_qq_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'moe_qq_url', array(
        'label'    => __( 'QQ链接', 'moe' ),
        'section'  => 'moe_social_links',
        'type'     => 'url',
    ) );

    // GitHub URL
    $wp_customize->add_setting( 'moe_github_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'moe_github_url', array(
        'label'    => __( 'GitHub链接', 'moe' ),
        'section'  => 'moe_social_links',
        'type'     => 'url',
    ) );

    // Footer Text
    $wp_customize->add_section( 'moe_footer', array(
        'title'    => __( '底部设置', 'moe' ),
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'moe_footer_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'moe_footer_text', array(
        'label'    => __( '底部文字', 'moe' ),
        'section'  => 'moe_footer',
        'type'     => 'textarea',
    ) );
}
add_action( 'customize_register', 'moe_customize_register' );

/**
 * Remove unnecessary WordPress default features
 */
// Remove emoji scripts
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Remove WordPress version
remove_action( 'wp_head', 'wp_generator' );

// Remove WLW manifest
remove_action( 'wp_head', 'wlwmanifest_link' );

// Remove RSD link
remove_action( 'wp_head', 'rsd_link' );

