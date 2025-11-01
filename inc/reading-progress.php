<?php
/**
 * 阅读进度条功能
 * Reading Progress Bar
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 在页面顶部输出进度条 HTML
 */
function moe_reading_progress_bar() {
    if ( ! is_single() ) {
        return;
    }
    ?>
    <div class="reading-progress-bar"></div>
    <?php
}
add_action( 'wp_body_open', 'moe_reading_progress_bar' );

/**
 * 添加进度条样式
 */
function moe_reading_progress_styles() {
    if ( ! is_single() ) {
        return;
    }
    ?>
    <style>
    /* 阅读进度条 */
    .reading-progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(to right, #24a5db, #FF6B9D);
        z-index: 99999;
        transition: width 0.1s ease-out;
        box-shadow: 0 2px 5px rgba(36, 165, 219, 0.3);
    }
    
    /* 让进度条在滚动时更平滑 */
    @supports (will-change: width) {
        .reading-progress-bar {
            will-change: width;
        }
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .reading-progress-bar {
            height: 2px;
        }
    }
    
    /* 可选：添加脉冲动画 */
    @keyframes progressPulse {
        0%, 100% {
            box-shadow: 0 2px 5px rgba(36, 165, 219, 0.3);
        }
        50% {
            box-shadow: 0 2px 10px rgba(36, 165, 219, 0.5);
        }
    }
    
    .reading-progress-bar.pulsing {
        animation: progressPulse 2s ease-in-out infinite;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_reading_progress_styles' );

/**
 * 添加进度条 JavaScript
 */
function moe_reading_progress_script() {
    if ( ! is_single() ) {
        return;
    }
    ?>
    <script>
    (function() {
        'use strict';
        
        var progressBar = document.querySelector('.reading-progress-bar');
        
        if (!progressBar) {
            return;
        }
        
        // 计算阅读进度
        function updateProgress() {
            // 获取文档高度
            var docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            
            // 获取当前滚动位置
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // 计算进度百分比
            var progress = (scrollTop / docHeight) * 100;
            
            // 限制在 0-100 之间
            progress = Math.max(0, Math.min(100, progress));
            
            // 更新进度条宽度
            progressBar.style.width = progress + '%';
            
            // 可选：当接近完成时添加脉冲效果
            if (progress > 95) {
                progressBar.classList.add('pulsing');
            } else {
                progressBar.classList.remove('pulsing');
            }
        }
        
        // 监听滚动事件（使用节流优化性能）
        var ticking = false;
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateProgress();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // 页面加载时更新一次
        updateProgress();
        
        // 窗口大小改变时重新计算
        window.addEventListener('resize', updateProgress);
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'moe_reading_progress_script', 5 );

/**
 * 在主题定制器中添加开关（可选）
 */
function moe_reading_progress_customizer( $wp_customize ) {
    // 检查是否已经有相关的 section
    if ( ! $wp_customize->get_section( 'moe_features' ) ) {
        $wp_customize->add_section( 'moe_features', array(
            'title'    => __( '功能开关', 'moe' ),
            'priority' => 35,
        ) );
    }
    
    // 添加阅读进度条开关
    $wp_customize->add_setting( 'moe_reading_progress', array(
        'default'           => true,
        'sanitize_callback' => 'moe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'moe_reading_progress', array(
        'label'    => __( '显示阅读进度条', 'moe' ),
        'description' => __( '在文章页面顶部显示阅读进度条', 'moe' ),
        'section'  => 'moe_features',
        'type'     => 'checkbox',
    ) );
}
// add_action( 'customize_register', 'moe_reading_progress_customizer' ); // 可选

/**
 * 根据定制器设置决定是否显示（如果启用了定制器开关）
 */
function moe_should_show_reading_progress() {
    return get_theme_mod( 'moe_reading_progress', true );
}

// 如果启用了定制器开关，可以用这个函数来控制
// 例如：
// if ( moe_should_show_reading_progress() ) {
//     // 显示进度条
// }

