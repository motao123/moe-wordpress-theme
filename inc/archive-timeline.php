<?php
/**
 * 归档时间轴功能
 * Archive Timeline
 * 
 * @package MOE
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加归档时间轴样式
 */
function moe_archive_timeline_styles() {
    if ( ! is_archive() ) {
        return;
    }
    ?>
    <style>
    /* 归档时间轴样式 */
    .archive-timeline {
        position: relative;
        padding: 20px 0 20px 60px;
    }
    
    .archive-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #24a5db, #FF6B9D);
        border-radius: 2px;
    }
    
    .timeline-year {
        position: relative;
        margin-bottom: 40px;
    }
    
    .year-label {
        position: relative;
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(135deg, #24a5db 0%, #1a8fbd 100%);
        color: #fff;
        font-size: 24px;
        font-weight: bold;
        border-radius: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(36, 165, 219, 0.3);
    }
    
    .year-label::before {
        content: '';
        position: absolute;
        left: -50px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background: #24a5db;
        border: 4px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 4px #24a5db;
    }
    
    .timeline-month {
        position: relative;
        margin-bottom: 30px;
    }
    
    .month-label {
        position: relative;
        display: inline-block;
        padding: 6px 15px;
        background: #f8f9fa;
        color: #666;
        font-size: 16px;
        font-weight: 600;
        border-radius: 15px;
        margin-bottom: 15px;
        border: 2px solid #e0e0e0;
    }
    
    .month-label::before {
        content: '';
        position: absolute;
        left: -50px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        background: #FF6B9D;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #FF6B9D;
    }
    
    .timeline-posts {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .timeline-post {
        position: relative;
        padding: 15px 20px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .timeline-post::before {
        content: '';
        position: absolute;
        left: -50px;
        top: 20px;
        width: 8px;
        height: 8px;
        background: #24a5db;
        border: 2px solid #fff;
        border-radius: 50%;
    }
    
    .timeline-post:hover {
        border-color: #24a5db;
        box-shadow: 0 4px 12px rgba(36, 165, 219, 0.2);
        transform: translateX(5px);
    }
    
    .timeline-post:hover::before {
        background: #FF6B9D;
        transform: scale(1.5);
    }
    
    .timeline-post-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .timeline-post-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .timeline-post-title a:hover {
        color: #24a5db;
    }
    
    .timeline-post-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 13px;
        color: #999;
    }
    
    .timeline-post-meta i {
        color: #24a5db;
        margin-right: 3px;
    }
    
    .timeline-post-meta a {
        color: #999;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .timeline-post-meta a:hover {
        color: #24a5db;
    }
    
    /* 统计信息 */
    .archive-stats {
        background: linear-gradient(135deg, var(--moe-header-gradient-start) 0%, var(--moe-header-gradient-end) 100%);
        border: 2px solid var(--moe-header-border-color);
        color: #333;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 40px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }
    
    .archive-stats-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: var(--moe-header-title-color);
    }
    
    .archive-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
    }
    
    .stat-item {
        padding: 15px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }
    
    .stat-number {
        font-size: 36px;
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: var(--moe-header-title-color);
    }
    
    .stat-label {
        font-size: 14px;
        color: #666;
    }
    
    /* 移动端优化 */
    @media screen and (max-width: 768px) {
        .archive-timeline {
            padding: 20px 0 20px 40px;
        }
        
        .archive-timeline::before {
            left: 10px;
        }
        
        .year-label {
            font-size: 20px;
            padding: 8px 16px;
        }
        
        .year-label::before {
            left: -40px;
            width: 16px;
            height: 16px;
        }
        
        .month-label {
            font-size: 14px;
            padding: 5px 12px;
        }
        
        .month-label::before {
            left: -40px;
            width: 10px;
            height: 10px;
        }
        
        .timeline-post::before {
            left: -40px;
            width: 6px;
            height: 6px;
        }
        
        .timeline-post {
            padding: 12px 15px;
        }
        
        .timeline-post-title {
            font-size: 15px;
        }
        
        .timeline-post-meta {
            font-size: 12px;
            flex-wrap: wrap;
        }
        
        .archive-stats {
            padding: 20px;
        }
        
        .archive-stats-title {
            font-size: 20px;
        }
        
        .stat-number {
            font-size: 28px;
        }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'moe_archive_timeline_styles' );

/**
 * 按年月分组归档文章
 */
function moe_get_posts_by_year_month( $query ) {
    $posts_by_date = array();
    
    while ( $query->have_posts() ) {
        $query->the_post();
        $year = get_the_date( 'Y' );
        $month = get_the_date( 'm' );
        $month_name = get_the_date( 'F' ); // 月份名称
        
        if ( ! isset( $posts_by_date[ $year ] ) ) {
            $posts_by_date[ $year ] = array();
        }
        
        if ( ! isset( $posts_by_date[ $year ][ $month ] ) ) {
            $posts_by_date[ $year ][ $month ] = array(
                'name'  => $month_name,
                'posts' => array()
            );
        }
        
        $posts_by_date[ $year ][ $month ]['posts'][] = array(
            'ID'       => get_the_ID(),
            'title'    => get_the_title(),
            'link'     => get_permalink(),
            'date'     => get_the_date(),
            'category' => get_the_category(),
            'comments' => get_comments_number(),
        );
    }
    
    wp_reset_postdata();
    
    // 按年份降序排序
    krsort( $posts_by_date );
    
    // 每年内的月份也按降序排序
    foreach ( $posts_by_date as $year => &$months ) {
        krsort( $months );
    }
    
    return $posts_by_date;
}

