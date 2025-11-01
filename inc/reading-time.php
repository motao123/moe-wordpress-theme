<?php
/**
 * 文章字数统计与阅读时间
 * Word Count and Reading Time
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 获取文章字数
 */
function moe_get_post_word_count( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field( 'post_content', $post_id );
    $content = strip_tags( $content );
    $content = strip_shortcodes( $content );
    
    // 统计中文字数（每个中文字符算一个字）
    $chinese_count = preg_match_all( '/[\x{4e00}-\x{9fa5}]/u', $content, $matches );
    
    // 统计英文单词数
    $english_words = preg_split( '/\s+/', trim( preg_replace( '/[\x{4e00}-\x{9fa5}]/u', '', $content ) ) );
    $english_count = count( array_filter( $english_words ) );
    
    // 总字数
    $total_count = $chinese_count + $english_count;
    
    return $total_count;
}

/**
 * 获取阅读时间（分钟）
 */
function moe_get_reading_time( $post_id = null ) {
    $word_count = moe_get_post_word_count( $post_id );
    
    // 假设阅读速度为 300 字/分钟
    $reading_speed = apply_filters( 'moe_reading_speed', 300 );
    $reading_time = ceil( $word_count / $reading_speed );
    
    // 至少 1 分钟
    $reading_time = max( 1, $reading_time );
    
    return $reading_time;
}

/**
 * 格式化字数显示
 */
function moe_format_word_count( $count ) {
    if ( $count >= 10000 ) {
        return number_format( $count / 10000, 1 ) . '万';
    } elseif ( $count >= 1000 ) {
        return number_format( $count / 1000, 1 ) . 'k';
    } else {
        return number_format( $count );
    }
}

/**
 * 显示文章字数和阅读时间
 */
function moe_display_reading_info( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $word_count = moe_get_post_word_count( $post_id );
    $reading_time = moe_get_reading_time( $post_id );
    $formatted_count = moe_format_word_count( $word_count );
    
    ?>
    <span class="reading-info">
        <i class="fa fa-file-text-o"></i>
        <span class="word-count"><?php printf( __( '约 %s 字', 'moe' ), $formatted_count ); ?></span>
        <span class="separator">|</span>
        <i class="fa fa-clock-o"></i>
        <span class="reading-time"><?php printf( __( '阅读约需 %s 分钟', 'moe' ), $reading_time ); ?></span>
    </span>
    <?php
}

/**
 * 在文章元信息中添加字数和阅读时间
 */
function moe_add_reading_info_to_meta() {
    if ( ! is_single() ) {
        return;
    }
    
    ?>
    <style>
    /* 字数和阅读时间样式 */
    .reading-info {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 14px;
    }
    
    .reading-info i {
        color: #24a5db;
    }
    
    .reading-info .separator {
        color: #ddd;
    }
    
    .reading-info .word-count,
    .reading-info .reading-time {
        font-weight: 500;
    }
    
    /* 在文章元信息列表中的样式 */
    .post-meta .reading-info {
        display: inline;
    }
    
    .post-meta .reading-info i {
        margin-right: 3px;
    }
    
    .post-meta .reading-info .separator {
        margin: 0 5px;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .reading-info {
            font-size: 13px;
            gap: 6px;
        }
        
        .reading-info i {
            font-size: 12px;
        }
    }
    
    @media screen and (max-width: 480px) {
        .reading-info {
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }
        
        .reading-info .separator {
            margin: 0 3px;
        }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_add_reading_info_to_meta' );

/**
 * 在文章管理列表中显示字数
 */
function moe_add_word_count_column( $columns ) {
    $columns['word_count'] = '<i class="fa fa-file-text-o"></i> ' . __( '字数', 'moe' );
    return $columns;
}
add_filter( 'manage_posts_columns', 'moe_add_word_count_column' );

/**
 * 显示字数列内容
 */
function moe_display_word_count_column( $column, $post_id ) {
    if ( 'word_count' === $column ) {
        $word_count = moe_get_post_word_count( $post_id );
        $reading_time = moe_get_reading_time( $post_id );
        echo '<strong>' . moe_format_word_count( $word_count ) . '</strong><br>';
        echo '<small>' . sprintf( __( '%s 分钟', 'moe' ), $reading_time ) . '</small>';
    }
}
add_action( 'manage_posts_custom_column', 'moe_display_word_count_column', 10, 2 );

/**
 * 使字数列可排序
 */
function moe_make_word_count_column_sortable( $columns ) {
    $columns['word_count'] = 'word_count';
    return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'moe_make_word_count_column_sortable' );

/**
 * 简化版：只返回字数
 */
function moe_get_word_count( $post_id = null ) {
    return moe_get_post_word_count( $post_id );
}

/**
 * 简化版：只显示阅读时间
 */
function moe_display_reading_time( $post_id = null ) {
    $reading_time = moe_get_reading_time( $post_id );
    ?>
    <span class="reading-time-only">
        <i class="fa fa-clock-o"></i>
        <?php printf( __( '%s 分钟', 'moe' ), $reading_time ); ?>
    </span>
    <?php
}

/**
 * 在REST API中添加字数和阅读时间字段
 */
function moe_register_rest_fields() {
    register_rest_field( 'post', 'word_count', array(
        'get_callback' => function( $post ) {
            return moe_get_post_word_count( $post['id'] );
        },
        'schema' => array(
            'description' => __( '文章字数', 'moe' ),
            'type'        => 'integer',
        ),
    ) );
    
    register_rest_field( 'post', 'reading_time', array(
        'get_callback' => function( $post ) {
            return moe_get_reading_time( $post['id'] );
        },
        'schema' => array(
            'description' => __( '阅读时间（分钟）', 'moe' ),
            'type'        => 'integer',
        ),
    ) );
}
add_action( 'rest_api_init', 'moe_register_rest_fields' );

