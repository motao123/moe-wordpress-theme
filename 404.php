<?php
/**
 * The template for displaying 404 pages (Not Found)
 * 
 * @package MOE
 * @since 1.0
 */

get_header();
?>

<div id="page">
    <article class="post error404">
        <h2 class="post-title">
            <i class="fa fa-exclamation-triangle"></i>
            <?php _e( '404 - 页面未找到', 'moe' ); ?>
        </h2>

        <div class="entry">
            <div class="error-404-content">
                <p class="error-message">
                    <?php _e( '抱歉，您访问的页面不存在或已被删除。', 'moe' ); ?>
                </p>

                <div class="error-suggestions">
                    <h3><?php _e( '可能的原因：', 'moe' ); ?></h3>
                    <ul>
                        <li><?php _e( '页面地址输入错误', 'moe' ); ?></li>
                        <li><?php _e( '页面已被移动或删除', 'moe' ); ?></li>
                        <li><?php _e( '链接已过期', 'moe' ); ?></li>
                    </ul>

                    <h3><?php _e( '您可以：', 'moe' ); ?></h3>
                    <ul>
                        <li>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <i class="fa fa-home"></i> <?php _e( '返回首页', 'moe' ); ?>
                            </a>
                        </li>
                        <li><?php _e( '使用下方搜索功能', 'moe' ); ?></li>
                        <li><?php _e( '查看最新文章', 'moe' ); ?></li>
                    </ul>
                </div>

                <!-- 搜索表单 -->
                <div class="error-search">
                    <h3><?php _e( '搜索内容', 'moe' ); ?></h3>
                    <?php get_search_form(); ?>
                </div>

                <!-- 推荐内容 -->
                <div class="error-recommendations">
                    <!-- 热门文章 -->
                    <?php
                    $popular_posts = new WP_Query( array(
                        'posts_per_page' => 5,
                        'post_status'    => 'publish',
                        'meta_key'       => 'post_views_count',
                        'orderby'        => 'meta_value_num',
                        'order'          => 'DESC',
                    ) );

                    if ( $popular_posts->have_posts() ) :
                    ?>
                        <div class="error-popular-posts">
                            <h3><i class="fa fa-fire"></i> <?php _e( '热门文章', 'moe' ); ?></h3>
                            <ul>
                                <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-angle-right"></i>
                                            <?php the_title(); ?>
                                            <span class="views-badge"><?php echo moe_get_post_views( get_the_ID() ); ?> <?php _e( '次', 'moe' ); ?></span>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    <?php
                    wp_reset_postdata();
                    endif;
                    ?>

                    <!-- 最新文章 -->
                    <?php
                    $recent_posts = wp_get_recent_posts( array(
                        'numberposts' => 5,
                        'post_status' => 'publish',
                    ) );

                    if ( $recent_posts ) :
                    ?>
                        <div class="error-recent-posts">
                            <h3><i class="fa fa-clock-o"></i> <?php _e( '最新文章', 'moe' ); ?></h3>
                            <ul>
                                <?php foreach ( $recent_posts as $post ) : ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                                            <i class="fa fa-angle-right"></i>
                                            <?php echo esc_html( $post['post_title'] ); ?>
                                            <span class="date-badge"><?php echo get_the_date( 'Y-m-d', $post['ID'] ); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php
                    wp_reset_postdata();
                    endif;
                    ?>
                </div>

                <!-- 分类列表 -->
                <?php
                $categories = get_categories( array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'number'     => 10,
                    'hide_empty' => true,
                ) );

                if ( $categories ) :
                ?>
                    <div class="error-categories">
                        <h3><?php _e( '浏览分类', 'moe' ); ?></h3>
                        <ul>
                            <?php foreach ( $categories as $category ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                                        <i class="fa fa-folder-o"></i>
                                        <?php echo esc_html( $category->name ); ?>
                                        (<?php echo $category->count; ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </article>
</div><!-- /#page -->

<?php get_footer(); ?>

<style>
.error404 {
    text-align: center;
    padding: 40px 20px;
}

.error404 .post-title {
    font-size: 48px;
    color: #FF6B9D;
    margin-bottom: 20px;
}

.error404 .post-title i {
    display: block;
    font-size: 80px;
    margin-bottom: 20px;
    animation: shake 2s ease infinite;
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-5deg); }
    20%, 40%, 60%, 80% { transform: rotate(5deg); }
}

.error-message {
    font-size: 18px;
    color: #666;
    margin: 30px 0;
    line-height: 1.6;
}

.error-suggestions,
.error-search,
.error-recommendations,
.error-categories {
    margin: 40px 0;
    text-align: left;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
}

.error-suggestions h3,
.error-search h3,
.error-recent-posts h3,
.error-popular-posts h3,
.error-categories h3 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.error-suggestions h3 i,
.error-search h3 i,
.error-recent-posts h3 i,
.error-popular-posts h3 i,
.error-categories h3 i {
    color: #24a5db;
}

.error-suggestions ul,
.error-recent-posts ul,
.error-categories ul {
    list-style: none;
    padding: 0;
}

.error-suggestions ul li,
.error-recent-posts ul li,
.error-categories ul li {
    padding: 8px 0;
    border-bottom: 1px dashed #eee;
}

.error-suggestions ul li:last-child,
.error-recent-posts ul li:last-child,
.error-categories ul li:last-child {
    border-bottom: none;
}

.error-suggestions ul li a,
.error-recent-posts ul li a,
.error-categories ul li a {
    color: #24a5db;
    transition: all 0.3s ease;
}

.error-suggestions ul li a:hover,
.error-recent-posts ul li a:hover,
.error-popular-posts ul li a:hover,
.error-categories ul li a:hover {
    color: #1a8fbd;
    padding-left: 5px;
}

.views-badge,
.date-badge {
    float: right;
    font-size: 12px;
    padding: 3px 8px;
    background: #24a5db;
    color: #fff;
    border-radius: 12px;
}

.date-badge {
    background: #FF6B9D;
}

.error-recommendations {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.error-search form {
    max-width: 500px;
    margin: 0 auto;
}

.error-search input[type="search"] {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.error-search input[type="search"]:focus {
    outline: none;
    border-color: #24a5db;
    box-shadow: 0 0 5px rgba(36, 165, 219, 0.3);
}

@media screen and (max-width: 768px) {
    .error404 {
        padding: 20px 10px;
    }
    
    .error404 .post-title {
        font-size: 36px;
    }
    
    .error404 .post-title i {
        font-size: 60px;
    }
    
    .error-message {
        font-size: 16px;
    }
    
    .error-suggestions,
    .error-search,
    .error-recommendations,
    .error-categories {
        padding: 20px;
    }
    
    .error-suggestions h3,
    .error-search h3,
    .error-recent-posts h3,
    .error-popular-posts h3,
    .error-categories h3 {
        font-size: 18px;
    }
    
    .error-recommendations {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .views-badge,
    .date-badge {
        float: none;
        display: inline-block;
        margin-left: 10px;
    }
}
</style>

