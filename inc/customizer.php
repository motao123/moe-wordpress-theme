<?php
/**
 * MOE主题定制器扩展
 * Theme Customizer Extensions
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 注册主题定制器设置
 */
function moe_customize_register_extended( $wp_customize ) {
    
    // ===================================================================
    // 颜色主题
    // ===================================================================
    $wp_customize->add_section( 'moe_colors', array(
        'title'    => __( '颜色主题', 'moe' ),
        'priority' => 30,
    ) );
    
    // 主题色
    $wp_customize->add_setting( 'moe_primary_color', array(
        'default'           => '#24a5db',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_primary_color', array(
        'label'    => __( '主题色', 'moe' ),
        'section'  => 'moe_colors',
        'settings' => 'moe_primary_color',
    ) ) );
    
    // 链接颜色
    $wp_customize->add_setting( 'moe_link_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_link_color', array(
        'label'    => __( '链接颜色', 'moe' ),
        'section'  => 'moe_colors',
        'settings' => 'moe_link_color',
    ) ) );
    
    // 页眉背景渐变起始色
    $wp_customize->add_setting( 'moe_header_gradient_start', array(
        'default'           => '#FFF5F7',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_header_gradient_start', array(
        'label'       => __( '页眉渐变起始色', 'moe' ),
        'description' => __( '用于归档、搜索等页面的页眉背景渐变起始色', 'moe' ),
        'section'     => 'moe_colors',
        'settings'    => 'moe_header_gradient_start',
    ) ) );
    
    // 页眉背景渐变结束色
    $wp_customize->add_setting( 'moe_header_gradient_end', array(
        'default'           => '#FFE8ED',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_header_gradient_end', array(
        'label'       => __( '页眉渐变结束色', 'moe' ),
        'description' => __( '用于归档、搜索等页面的页眉背景渐变结束色', 'moe' ),
        'section'     => 'moe_colors',
        'settings'    => 'moe_header_gradient_end',
    ) ) );
    
    // 页眉边框颜色
    $wp_customize->add_setting( 'moe_header_border_color', array(
        'default'           => '#FFB6C1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_header_border_color', array(
        'label'       => __( '页眉边框颜色', 'moe' ),
        'description' => __( '用于归档、搜索等页面的页眉边框颜色', 'moe' ),
        'section'     => 'moe_colors',
        'settings'    => 'moe_header_border_color',
    ) ) );
    
    // 页眉标题颜色
    $wp_customize->add_setting( 'moe_header_title_color', array(
        'default'           => '#E91E63',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'moe_header_title_color', array(
        'label'       => __( '页眉标题颜色', 'moe' ),
        'description' => __( '用于归档、搜索等页面的页眉标题颜色', 'moe' ),
        'section'     => 'moe_colors',
        'settings'    => 'moe_header_title_color',
    ) ) );
    
    // ===================================================================
    // 网站背景图片
    // ===================================================================
    $wp_customize->add_section( 'moe_site_background', array(
        'title'    => __( '网站背景图片', 'moe' ),
        'priority' => 35,
    ) );
    
    // 网站背景图片
    $wp_customize->add_setting( 'moe_site_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'moe_site_bg_image', array(
        'label'       => __( '网站背景图片', 'moe' ),
        'description' => __( '上传网站整体背景图片，推荐尺寸：1920x1080px或更大', 'moe' ),
        'section'     => 'moe_site_background',
        'settings'    => 'moe_site_bg_image',
    ) ) );
    
    // 背景图片显示模式
    $wp_customize->add_setting( 'moe_site_bg_size', array(
        'default'           => 'cover',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_site_bg_size', array(
        'label'   => __( '背景图片显示模式', 'moe' ),
        'section' => 'moe_site_background',
        'type'    => 'select',
        'choices' => array(
            'cover'   => __( '覆盖（推荐）', 'moe' ),
            'contain' => __( '包含', 'moe' ),
            'auto'    => __( '原始大小', 'moe' ),
        ),
    ) );
    
    // 背景图片位置
    $wp_customize->add_setting( 'moe_site_bg_position', array(
        'default'           => 'center center',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_site_bg_position', array(
        'label'   => __( '背景图片位置', 'moe' ),
        'section' => 'moe_site_background',
        'type'    => 'select',
        'choices' => array(
            'left top'      => __( '左上', 'moe' ),
            'center top'    => __( '居中上', 'moe' ),
            'right top'     => __( '右上', 'moe' ),
            'left center'   => __( '左中', 'moe' ),
            'center center' => __( '居中（推荐）', 'moe' ),
            'right center'  => __( '右中', 'moe' ),
            'left bottom'   => __( '左下', 'moe' ),
            'center bottom' => __( '居中下', 'moe' ),
            'right bottom'  => __( '右下', 'moe' ),
        ),
    ) );
    
    // 背景图片重复
    $wp_customize->add_setting( 'moe_site_bg_repeat', array(
        'default'           => 'no-repeat',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_site_bg_repeat', array(
        'label'   => __( '背景图片重复', 'moe' ),
        'section' => 'moe_site_background',
        'type'    => 'select',
        'choices' => array(
            'no-repeat' => __( '不重复（推荐）', 'moe' ),
            'repeat'    => __( '重复', 'moe' ),
            'repeat-x'  => __( '水平重复', 'moe' ),
            'repeat-y'  => __( '垂直重复', 'moe' ),
        ),
    ) );
    
    // 背景图片固定
    $wp_customize->add_setting( 'moe_site_bg_attachment', array(
        'default'           => 'fixed',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_site_bg_attachment', array(
        'label'   => __( '背景图片固定', 'moe' ),
        'section' => 'moe_site_background',
        'type'    => 'select',
        'choices' => array(
            'scroll' => __( '滚动', 'moe' ),
            'fixed'  => __( '固定（推荐）', 'moe' ),
        ),
    ) );
    
    // 背景图片透明度
    $wp_customize->add_setting( 'moe_site_bg_opacity', array(
        'default'           => '1',
        'sanitize_callback' => 'moe_sanitize_float',
    ) );
    
    $wp_customize->add_control( 'moe_site_bg_opacity', array(
        'label'       => __( '背景图片透明度', 'moe' ),
        'description' => __( '调整背景图片的透明度（0-1，1为完全不透明）', 'moe' ),
        'section'     => 'moe_site_background',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ),
    ) );
    
    // ===================================================================
    // 个人信息区域设置
    // ===================================================================
    $wp_customize->add_section( 'moe_header_profile', array(
        'title'    => __( '个人信息区域', 'moe' ),
        'priority' => 36,
    ) );
    
    // 背景图片
    $wp_customize->add_setting( 'moe_header_bg_image', array(
        'default'           => get_template_directory_uri() . '/images/default.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'moe_header_bg_image', array(
        'label'       => __( '背景图片', 'moe' ),
        'description' => __( '上传个人信息区域的背景图片，推荐尺寸：1920x600px。默认使用 default.jpg，您可以上传自己的图片替换。', 'moe' ),
        'section'     => 'moe_header_profile',
        'settings'    => 'moe_header_bg_image',
    ) ) );
    
    // 背景图片显示模式
    $wp_customize->add_setting( 'moe_header_bg_size', array(
        'default'           => 'cover',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_header_bg_size', array(
        'label'   => __( '背景图片显示模式', 'moe' ),
        'section' => 'moe_header_profile',
        'type'    => 'select',
        'choices' => array(
            'cover'   => __( '覆盖（推荐）', 'moe' ),
            'contain' => __( '包含', 'moe' ),
            'auto'    => __( '原始大小', 'moe' ),
        ),
    ) );
    
    // 背景图片位置
    $wp_customize->add_setting( 'moe_header_bg_position', array(
        'default'           => 'center center',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_header_bg_position', array(
        'label'   => __( '背景图片位置', 'moe' ),
        'section' => 'moe_header_profile',
        'type'    => 'select',
        'choices' => array(
            'left top'      => __( '左上', 'moe' ),
            'center top'    => __( '居中上', 'moe' ),
            'right top'     => __( '右上', 'moe' ),
            'left center'   => __( '左中', 'moe' ),
            'center center' => __( '居中（推荐）', 'moe' ),
            'right center'  => __( '右中', 'moe' ),
            'left bottom'   => __( '左下', 'moe' ),
            'center bottom' => __( '居中下', 'moe' ),
            'right bottom'  => __( '右下', 'moe' ),
        ),
    ) );
    
    // 背景图片重复
    $wp_customize->add_setting( 'moe_header_bg_repeat', array(
        'default'           => 'no-repeat',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_header_bg_repeat', array(
        'label'   => __( '背景图片重复', 'moe' ),
        'section' => 'moe_header_profile',
        'type'    => 'select',
        'choices' => array(
            'no-repeat' => __( '不重复（推荐）', 'moe' ),
            'repeat'    => __( '重复', 'moe' ),
            'repeat-x'  => __( '水平重复', 'moe' ),
            'repeat-y'  => __( '垂直重复', 'moe' ),
        ),
    ) );
    
    // 背景图片固定
    $wp_customize->add_setting( 'moe_header_bg_attachment', array(
        'default'           => 'scroll',
        'sanitize_callback' => 'moe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'moe_header_bg_attachment', array(
        'label'   => __( '背景图片固定', 'moe' ),
        'section' => 'moe_header_profile',
        'type'    => 'select',
        'choices' => array(
            'scroll' => __( '滚动', 'moe' ),
            'fixed'  => __( '固定（视差效果）', 'moe' ),
        ),
    ) );
    
    // 背景遮罩透明度
    $wp_customize->add_setting( 'moe_header_bg_overlay', array(
        'default'           => '0.3',
        'sanitize_callback' => 'moe_sanitize_float',
    ) );
    
    $wp_customize->add_control( 'moe_header_bg_overlay', array(
        'label'       => __( '背景遮罩透明度', 'moe' ),
        'description' => __( '为背景添加深色遮罩，增强文字可读性（0-1，0为无遮罩）', 'moe' ),
        'section'     => 'moe_header_profile',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 1,
            'step' => 0.1,
        ),
    ) );
    
    // ===================================================================
    // 字体设置
    // ===================================================================
    $wp_customize->add_section( 'moe_typography', array(
        'title'    => __( '字体设置', 'moe' ),
        'priority' => 40,
    ) );
    
    // 正文字体大小
    $wp_customize->add_setting( 'moe_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'moe_body_font_size', array(
        'label'       => __( '正文字体大小 (px)', 'moe' ),
        'section'     => 'moe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 20,
            'step' => 1,
        ),
    ) );
    
    // ===================================================================
    // 页脚设置
    // ===================================================================
    $wp_customize->add_section( 'moe_footer', array(
        'title'    => __( '页脚设置', 'moe' ),
        'priority' => 50,
    ) );
    
    // 自定义页脚版权信息
    $wp_customize->add_setting( 'moe_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    
    $wp_customize->add_control( 'moe_footer_copyright', array(
        'label'       => __( '自定义页脚版权信息', 'moe' ),
        'description' => __( '留空则使用默认版权信息，支持HTML标签', 'moe' ),
        'section'     => 'moe_footer',
        'type'        => 'textarea',
    ) );
    
    // ICP备案号
    $wp_customize->add_setting( 'moe_footer_icp', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'moe_footer_icp', array(
        'label'   => __( 'ICP备案号', 'moe' ),
        'section' => 'moe_footer',
        'type'    => 'text',
    ) );
    
    // 公安备案号
    $wp_customize->add_setting( 'moe_footer_gaba', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'moe_footer_gaba', array(
        'label'   => __( '公安备案号', 'moe' ),
        'section' => 'moe_footer',
        'type'    => 'text',
    ) );
    
    // 公安备案跳转链接
    $wp_customize->add_setting( 'moe_footer_gaba_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'moe_footer_gaba_url', array(
        'label'   => __( '公安备案跳转链接', 'moe' ),
        'section' => 'moe_footer',
        'type'    => 'url',
    ) );
    
    // 显示友情链接
    $wp_customize->add_setting( 'moe_footer_links', array(
        'default'           => false,
        'sanitize_callback' => 'moe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'moe_footer_links', array(
        'label'       => __( '显示友情链接', 'moe' ),
        'description' => __( '可以通过菜单或WordPress链接管理器添加', 'moe' ),
        'section'     => 'moe_footer',
        'type'        => 'checkbox',
    ) );
    
    // 显示主题版权信息
    $wp_customize->add_setting( 'moe_footer_theme_credit', array(
        'default'           => true,
        'sanitize_callback' => 'moe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'moe_footer_theme_credit', array(
        'label'   => __( '显示主题版权信息', 'moe' ),
        'section' => 'moe_footer',
        'type'    => 'checkbox',
    ) );
    
    // 显示网站加载时间
    $wp_customize->add_setting( 'moe_footer_load_time', array(
        'default'           => false,
        'sanitize_callback' => 'moe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'moe_footer_load_time', array(
        'label'   => __( '显示网站加载时间', 'moe' ),
        'section' => 'moe_footer',
        'type'    => 'checkbox',
    ) );
}
add_action( 'customize_register', 'moe_customize_register_extended' );

/**
 * 清理函数
 */
function moe_sanitize_select( $input, $setting ) {
    $input   = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function moe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function moe_sanitize_float( $input ) {
    $input = floatval( $input );
    return ( $input >= 0 && $input <= 1 ) ? $input : 1;
}

/**
 * 输出自定义CSS
 */
function moe_customizer_css() {
    $primary_color = get_theme_mod( 'moe_primary_color', '#24a5db' );
    $link_color    = get_theme_mod( 'moe_link_color', '#000000' );
    $font_size     = get_theme_mod( 'moe_body_font_size', '16' );
    
    // 页眉颜色设置
    $header_gradient_start = get_theme_mod( 'moe_header_gradient_start', '#FFF5F7' );
    $header_gradient_end   = get_theme_mod( 'moe_header_gradient_end', '#FFE8ED' );
    $header_border_color   = get_theme_mod( 'moe_header_border_color', '#FFB6C1' );
    $header_title_color    = get_theme_mod( 'moe_header_title_color', '#E91E63' );
    
    // 网站背景图片设置
    $site_bg_image      = get_theme_mod( 'moe_site_bg_image', '' );
    $site_bg_size       = get_theme_mod( 'moe_site_bg_size', 'cover' );
    $site_bg_position   = get_theme_mod( 'moe_site_bg_position', 'center center' );
    $site_bg_repeat     = get_theme_mod( 'moe_site_bg_repeat', 'no-repeat' );
    $site_bg_attachment = get_theme_mod( 'moe_site_bg_attachment', 'fixed' );
    $site_bg_opacity    = get_theme_mod( 'moe_site_bg_opacity', '1' );
    
    // 个人信息区域背景设置
    $header_bg_image      = get_theme_mod( 'moe_header_bg_image', get_template_directory_uri() . '/images/default.jpg' );
    $header_bg_size       = get_theme_mod( 'moe_header_bg_size', 'cover' );
    $header_bg_position   = get_theme_mod( 'moe_header_bg_position', 'center center' );
    $header_bg_repeat     = get_theme_mod( 'moe_header_bg_repeat', 'no-repeat' );
    $header_bg_attachment = get_theme_mod( 'moe_header_bg_attachment', 'scroll' );
    $header_bg_overlay    = get_theme_mod( 'moe_header_bg_overlay', '0.3' );
    
    ?>
    <style type="text/css">
        :root {
            --moe-primary-color: <?php echo esc_attr( $primary_color ); ?>;
            --moe-link-color: <?php echo esc_attr( $link_color ); ?>;
            --moe-font-size: <?php echo esc_attr( $font_size ); ?>px;
            --moe-header-gradient-start: <?php echo esc_attr( $header_gradient_start ); ?>;
            --moe-header-gradient-end: <?php echo esc_attr( $header_gradient_end ); ?>;
            --moe-header-border-color: <?php echo esc_attr( $header_border_color ); ?>;
            --moe-header-title-color: <?php echo esc_attr( $header_title_color ); ?>;
        }
        
        <?php if ( ! empty( $site_bg_image ) ) : ?>
        /* 网站背景图片 */
        body {
            position: relative;
            background-image: url('<?php echo esc_url( $site_bg_image ); ?>');
            background-size: <?php echo esc_attr( $site_bg_size ); ?>;
            background-position: <?php echo esc_attr( $site_bg_position ); ?>;
            background-repeat: <?php echo esc_attr( $site_bg_repeat ); ?>;
            background-attachment: <?php echo esc_attr( $site_bg_attachment ); ?>;
            font-size: var(--moe-font-size);
        }
        
        <?php if ( floatval( $site_bg_opacity ) < 1 ) : ?>
        /* 背景图片透明度控制 */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('<?php echo esc_url( $site_bg_image ); ?>');
            background-size: <?php echo esc_attr( $site_bg_size ); ?>;
            background-position: <?php echo esc_attr( $site_bg_position ); ?>;
            background-repeat: <?php echo esc_attr( $site_bg_repeat ); ?>;
            background-attachment: <?php echo esc_attr( $site_bg_attachment ); ?>;
            opacity: <?php echo esc_attr( $site_bg_opacity ); ?>;
            z-index: -1;
            pointer-events: none;
        }
        
        body {
            background: none;
        }
        <?php endif; ?>
        <?php else : ?>
        body {
            font-size: var(--moe-font-size);
        }
        <?php endif; ?>
        
        a {
            color: var(--moe-link-color);
        }
        
        ::selection {
            background: var(--moe-primary-color);
        }
        
        #top,
        .post-meta a:hover,
        #pagenavi .page-numbers:hover,
        #pagenavi .page-numbers.current {
            background: var(--moe-primary-color);
        }
        
        .post-title a:hover {
            color: var(--moe-primary-color);
        }
        
        <?php if ( ! empty( $header_bg_image ) ) : ?>
        /* 个人信息区域背景图片 */
        #head {
            position: relative;
            background-image: url('<?php echo esc_url( $header_bg_image ); ?>');
            background-size: <?php echo esc_attr( $header_bg_size ); ?>;
            background-position: <?php echo esc_attr( $header_bg_position ); ?>;
            background-repeat: <?php echo esc_attr( $header_bg_repeat ); ?>;
            background-attachment: <?php echo esc_attr( $header_bg_attachment ); ?>;
            padding: 60px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        /* 保留毛玻璃背景层（覆盖默认样式，增强效果） */
        #head::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            max-width: 650px;
            height: 85%;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 0;
        }
        
        <?php if ( floatval( $header_bg_overlay ) > 0 ) : ?>
        /* 深色遮罩层（使用::after，不影响毛玻璃效果） */
        #head::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, <?php echo esc_attr( $header_bg_overlay ); ?>);
            z-index: 0;
            border-radius: 8px;
        }
        
        /* 增强文字可读性 */
        #head-about,
        #head a,
        #head .wocao a {
            color: #ffffff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5),
                         0 0 20px rgba(0, 0, 0, 0.3);
        }
        
        #head .wocao a {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        #head .wocao a:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
        }
        <?php endif; ?>
        
        /* 确保内容在所有背景层之上 */
        #head > * {
            position: relative;
            z-index: 1;
        }
        
        /* 响应式调整 */
        @media screen and (max-width: 768px) {
            #head {
                padding: 40px 15px;
            }
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action( 'wp_head', 'moe_customizer_css' );
